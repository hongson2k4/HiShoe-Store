<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = [];
        $subtotal = 0;
        $discount = 0;
        $total = 0;

        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)
                ->with(['productVariant.product.brand', 'productVariant.size', 'productVariant.color'])
                ->get();
        } else {
            $sessionCart = Session::get('cart', []);

            if (!empty($sessionCart)) {
                foreach ($sessionCart as $item) {
                    $productVariant = ProductVariant::with(['product.brand', 'size', 'color'])
                        ->find($item['product_variant_id']);

                    if ($productVariant) {
                        $cartItem = new \stdClass();
                        $cartItem->id = $item['id'];
                        $cartItem->quantity = $item['quantity'];
                        $cartItem->price = $productVariant->price;
                        $cartItem->productVariant = $productVariant;
                        $cartItem->product = $productVariant->product;

                        $cartItems[] = $cartItem;
                    }
                }
            }
        }

        foreach ($cartItems as $item) {
            $subtotal += $item->productVariant->price * $item->quantity;
        }

        $appliedVoucher = Session::get('applied_voucher');
        if ($appliedVoucher) {
            $voucher = Voucher::where('code', $appliedVoucher)->first();
            if ($voucher) {
                if ($voucher->discount_type == 'percentage') {
                    $discount = $subtotal * ($voucher->discount_value / 100);
                } else {
                    $discount = $voucher->discount_value;
                }
            }
        }

        $total = $subtotal - $discount;

        return view('client.checkout.check', compact('cartItems', 'subtotal', 'discount', 'total'));
    }


    public function process(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|string|in:vnpay,bank-transfer,cod',
            'total' => 'required|numeric',
            'bank_code' => 'nullable|string',
            'card_number' => 'nullable|string',
            'card_holder' => 'nullable|string',
            'card_expiry' => 'nullable|string',
        ]);

        // Tạo đơn hàng
        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_price = $request->total;
        $order->status = 0; // 0: Chưa thanh toán
        $order->shipping_address = $request->address;
        $order->voucher_id = Session::get('applied_voucher') ?? null;
        $order->save();

        // Lưu thông tin thanh toán
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $request->total;
        $payment->payment_status = 0; // 0: Chưa thanh toán
        $payment->save();

        // Xử lý thanh toán bằng VNPAY
        if ($request->payment_method == 'vnpay') {
            $vnp_TmnCode = env('VNPAY_TMN_CODE'); // Mã website tại VNPAY
            $vnp_HashSecret = env('VNPAY_HASH_SECRET'); // Chuỗi bí mật
            $vnp_Url = env('VNPAY_URL');
            $vnp_Returnurl = env('VNPAY_RETURN_URL');
            $vnp_TxnRef = $order->id; // Mã đơn hàng
            $vnp_OrderInfo = "Thanh toán đơn hàng " . $order->id;
            $vnp_OrderType = "billpayment";
            $vnp_Amount = $request->total * 100; // Số tiền thanh toán (đơn vị: VND)
            $vnp_Locale = "vn";
            $vnp_BankCode = $request->bank_code;
            $vnp_IpAddr = request()->ip();

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHashType=HMACSHA512&vnp_SecureHash=' . $vnpSecureHash;
            }

            return redirect($vnp_Url);
        }

        // Cập nhật trạng thái thanh toán cho COD và bank-transfer
        if ($request->payment_method == 'cod' || $request->payment_method == 'bank-transfer') {
            $payment->payment_status = 1; // 1: Đã thanh toán
        }

        $payment->save();

        // Sau khi xử lý thanh toán thành công, xóa giỏ hàng và voucher
        $user = Auth::user();
        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            Session::forget('cart');
        }
        Session::forget('applied_voucher');

        return redirect()->route('checkout.success', ['order_id' => $order->id]);
    }



public function success(Request $request)
{
    // Lấy các tham số từ VNPAY
    $vnp_ResponseCode = $request->input('vnp_ResponseCode');
    $vnp_TxnRef = $request->input('vnp_TxnRef');
    $vnp_TransactionNo = $request->input('vnp_TransactionNo');
    $vnp_Amount = $request->input('vnp_Amount');
    $vnp_BankCode = $request->input('vnp_BankCode');
    $vnp_PayDate = $request->input('vnp_PayDate');
    $vnp_OrderInfo = $request->input('vnp_OrderInfo');
    $vnp_SecureHash = $request->input('vnp_SecureHash');

    // Kiểm tra mã hash để đảm bảo tính toàn vẹn của dữ liệu
    $vnp_HashSecret = env('VNPAY_HASH_SECRET');
    $inputData = $request->except('vnp_SecureHash');
    ksort($inputData);
    $hashData = '';
    foreach ($inputData as $key => $value) {
        $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
    }
    $hashData = ltrim($hashData, '&');
    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    if ($secureHash === $vnp_SecureHash) {
        if ($vnp_ResponseCode == '00') {
            // Thanh toán thành công
            $order = Order::find($vnp_TxnRef);
            if ($order) {
                $order->status = 1; // Cập nhật trạng thái đơn hàng
                $order->save();

                $payment = Payment::where('order_id', $order->id)->first();
                if ($payment) {
                    $payment->payment_status = 1; // Cập nhật trạng thái thanh toán
                    $payment->save();
                }

                // Xóa giỏ hàng sau khi thanh toán thành công
                $user = Auth::user();
                if ($user) {
                    Cart::where('user_id', $user->id)->delete();
                } else {
                    Session::forget('cart');
                }
                Session::forget('applied_voucher');

                return view('client.checkout.success', compact('order', 'payment'));
            }
        } else {
            // Thanh toán thất bại
            return redirect()->route('home')->with('error', 'Thanh toán thất bại.');
        }
    } else {
        // Mã hash không hợp lệ
        return redirect()->route('home')->with('error', 'Dữ liệu không hợp lệ.');
    }
}
}

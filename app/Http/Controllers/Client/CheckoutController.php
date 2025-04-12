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
            'fullname' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\d{10,15}$/',
            'payment_method' => 'required|string|in:vnpay,bank-transfer,cod',
            'total' => 'required|numeric',
            'voucher_code' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'card_number' => 'nullable|string',
            'card_holder' => 'nullable|string',
            'card_expiry' => 'nullable|string',
        ]);

        $voucherCode = $request->voucher_code;
        $discount = 0;

        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)
                ->where('status', 1)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($voucher) {
                $subtotal = $request->total;
                if ($subtotal >= $voucher->min_order_value) {
                    if ($voucher->discount_type == 'percentage') {
                        $discount = min($subtotal * ($voucher->discount_value / 100), $voucher->max_discount_value);
                    } else {
                        $discount = min($voucher->discount_value, $voucher->max_discount_value);
                    }

                    // Giảm giá từ voucher
                    $request->merge(['total' => $subtotal - $discount]);

                    // Giảm số lượng sử dụng voucher
                    $voucher->usage_limit -= 1;
                    if ($voucher->usage_limit <= 0) {
                        $voucher->status = 0;
                    }
                    $voucher->save();
                } else {
                    return back()->withErrors(['voucher_code' => 'Giá trị đơn hàng không đủ để áp dụng mã giảm giá.']);
                }
            } else {
                return back()->withErrors(['voucher_code' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
            }
        }


        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_price = $request->total;
        $order->status = 0;
        $order->shipping_address = $request->address;
        $order->voucher_id = Session::get('applied_voucher') ?? null;
        $order->save();


        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->payment_method = $request->payment_method;
        $payment->amount = $request->total;
        $payment->payment_status = 0;
        $payment->save();


        if ($request->payment_method == 'vnpay') {
            $vnp_TmnCode = env('VNPAY_TMN_CODE');
            $vnp_HashSecret = env('VNPAY_HASH_SECRET');
            $vnp_Url = env('VNPAY_URL');
            $vnp_Returnurl = env('VNPAY_RETURN_URL');
            $vnp_TxnRef = $order->id;
            $vnp_OrderInfo = "Thanh toán đơn hàng " . $order->id;
            $vnp_OrderType = "billpayment";
            $vnp_Amount = $request->total * 100;
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
            $hashdata = '';
            foreach ($inputData as $key => $value) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            }
            $hashdata = ltrim($hashdata, '&');

            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url = $vnp_Url . "?" . http_build_query($inputData) . '&vnp_SecureHashType=HMACSHA512&vnp_SecureHash=' . $vnpSecureHash;

            return redirect($vnp_Url);
        }


        if ($request->payment_method == 'cod' || $request->payment_method == 'bank-transfer') {
            $payment->payment_status = 1;
            $payment->save();


            $user = Auth::user();
            if ($user) {
                Cart::where('user_id', $user->id)->delete();
            } else {
                Session::forget('cart');
            }
            Session::forget('applied_voucher');


            return redirect()->route('checkout.success', ['order_id' => $order->id]);
        }
    }



    public function success(Request $request)
    {

        $orderId = $request->input('order_id');
        if ($orderId) {
            $order = Order::find($orderId);
            $payment = Payment::where('order_id', $orderId)->first();

            if ($order) {
                return view('client.checkout.success', compact('order', 'payment'));
            }

            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
        }


        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_SecureHash = $request->input('vnp_SecureHash');

        if ($vnp_ResponseCode && $vnp_TxnRef && $vnp_SecureHash) {
            $vnp_HashSecret = env('VNPAY_HASH_SECRET');
            $inputData = $request->except('vnp_SecureHash');
            ksort($inputData);
            $hashData = '';
            foreach ($inputData as $key => $value) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            }
            $hashData = ltrim($hashData, '&');
            $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

            if ($secureHash === $vnp_SecureHash && $vnp_ResponseCode == '00') {
                $order = Order::find($vnp_TxnRef);
                if ($order) {
                    $order->status = 1;
                    $order->save();

                    $payment = Payment::where('order_id', $order->id)->first();
                    if ($payment) {
                        $payment->payment_status = 1;
                        $payment->save();
                    }


                    $user = Auth::user();
                    if ($user) {
                        Cart::where('user_id', $user->id)->delete();
                    } else {
                        Session::forget('cart');
                    }
                    Session::forget('applied_voucher');

                    return view('client.checkout.success', compact('order', 'payment'));
                }
            }

            return redirect()->route('home')->with('error', 'Thanh toán thất bại.');
        }

        return redirect()->route('home')->with('error', 'Dữ liệu không hợp lệ.');
    }
}

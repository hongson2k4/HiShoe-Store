<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\OrderItemHistory;
use App\Models\Product_variant;
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
                    $productVariant = Product_variant::with(['product.brand', 'size', 'color'])
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

        // Kiểm tra voucher đã áp dụng từ session
        $appliedVoucherId = Session::get('applied_voucher_id');
        if ($appliedVoucherId) {
            $voucher = Voucher::find($appliedVoucherId);
            if ($voucher) {
                if ($voucher->discount_type == 0) { // Giảm giá theo %
                    $discount = min($subtotal * ($voucher->discount_value / 100), $voucher->max_discount_value ?? PHP_FLOAT_MAX);
                } else { // Giảm giá cố định
                    $discount = min($voucher->discount_value, $voucher->max_discount_value ?? PHP_FLOAT_MAX);
                }
            } else {
                // Nếu voucher không hợp lệ, xóa khỏi session
                Session::forget('applied_voucher_id');
                Session::forget('voucher_code');
            }
        }

        $total = $subtotal - $discount;

        return view('client.checkout.check', compact('cartItems', 'subtotal', 'discount', 'total'));
    }

    public function applyVoucher(Request $request)
    {
        $voucherCode = $request->input('voucher_code');
        $subtotal = 0;

        $user = Auth::user();
        $cartItems = $user ? Cart::where('user_id', $user->id)->get() : Session::get('cart', []);
        if ($user) {
            foreach ($cartItems as $item) {
                $subtotal += $item->productVariant->price * $item->quantity;
            }
        } else {
            foreach ($cartItems as $item) {
                $productVariant = Product_variant::find($item['product_variant_id']);
                if ($productVariant) {
                    $subtotal += $productVariant->price * $item['quantity'];
                }
            }
        }

        // Kiểm tra voucher
        $voucher = Voucher::where('code', $voucherCode)
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
        }

        if ($subtotal < $voucher->min_order_value) {
            return response()->json(['success' => false, 'message' => 'Giá trị đơn hàng không đủ để áp dụng mã giảm giá.']);
        }

        $discount = 0;
        if ($voucher->discount_type == 0) { // Giảm giá theo %
            $discount = $subtotal * ($voucher->discount_value / 100);

            if ($voucher->max_discount_value !== null) {
                $discount = min($discount, $voucher->max_discount_value);
            }
        } else { // Giảm giá cố định
            $discount = $voucher->discount_value;

            if ($voucher->max_discount_value !== null) {
                $discount = min($discount, $voucher->max_discount_value);
            }
        }

        // Lưu mã giảm giá và ID vào session
        Session::put('applied_voucher_id', $voucher->id);
        Session::put('voucher_code', $voucherCode);

        // Giảm số lần sử dụng của voucher
        $voucher->usage_limit -= 1;
        if ($voucher->usage_limit <= 0) {
            $voucher->status = 0;
        }
        $voucher->save();

        return response()->json(['success' => true, 'discount' => number_format($discount)]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^0\d{9,10}$/',
            'payment_method' => 'required|string|in:vnpay,cod',
            'total' => 'required|numeric',
        ], [
            'first_name.required' => 'Vui lòng nhập họ của bạn.',
            'first_name.regex' => 'Họ chỉ được chứa chữ cái, khoảng trắng và có thể có dấu.',
            'last_name.required' => 'Vui lòng nhập tên của bạn.',
            'last_name.regex' => 'Tên chỉ được chứa chữ cái, khoảng trắng và có thể có dấu.',
            'address.required' => 'Vui lòng nhập địa chỉ của bạn.',
            'phone.required' => 'Vui lòng nhập số điện thoại của bạn.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 0 và có độ dài từ 10 đến 11 chữ số.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'total.required' => 'Tổng tiền không được để trống.',
            'total.numeric' => 'Tổng tiền phải là số.',
        ]);

        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_price = $request->total;
        $order->status = 0;
        $order->shipping_address = $request->address;
        // Sử dụng ID của voucher từ session
        $order->voucher_id = Session::get('applied_voucher_id');
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

        if ($request->payment_method == 'cod') {
            $payment->payment_status = 1;
            $payment->save();

            Session::forget('applied_voucher_id');

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
                $order->status = 1;
                $order->save();
                $cartItems = Cart::where('user_id', Auth::guard('web')->id())
                    ->with(['productVariant.product'])
                    ->get();

                foreach ($cartItems as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $item->productVariant->id,
                        'quantity' => $item->quantity,
                        'price' => $item->productVariant->price,

                    ]);

                    OrderItemHistory::create([
                        'order_id' => $order->id,
                        'product_id' => $item->productVariant->product->id,
                        'quantity' => $item->quantity,
                        'price' => $item->productVariant->price,
                    ]);
                }

                $user = Auth::user();
                if ($user) {
                    Cart::where('user_id', $user->id)->delete();
                } else {
                    Session::forget('cart');
                }

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
                    $cartItems = Cart::where('user_id', Auth::guard('web')->id())
                        ->with(['productVariant.product'])
                        ->get();

                    foreach ($cartItems as $item) {
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'product_variant_id' => $item->productVariant->id,
                            'quantity' => $item->quantity,
                            'price' => $item->productVariant->price,

                        ]);

                        OrderItemHistory::create([
                            'order_id' => $order->id,
                            'product_id' => $item->productVariant->product->id,
                            'quantity' => $item->quantity,
                            'price' => $item->productVariant->price,
                        ]);
                    }
                    $user = Auth::user();
                    if ($user) {
                        Cart::where('user_id', $user->id)->delete();
                    } else {
                        Session::forget('cart');
                    }
                    Session::forget('applied_voucher_id');

                    return view('client.checkout.success', compact('order', 'payment'));
                }
            }

            return redirect()->route('home')->with('error', 'Thanh toán thất bại.');
        }

        return redirect()->route('home')->with('error', 'Dữ liệu không hợp lệ.');
    }
}
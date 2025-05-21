<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderItemHistory;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class ReviewController extends Controller
{
   
    public function create($order_id, $product_id)
{
    // Tìm đơn hàng theo order_id và kiểm tra người dùng có sở hữu đơn hàng đó không
    $order = Order::where('id', $order_id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // Tìm sản phẩm cần đánh giá
    $product = Products::findOrFail($product_id);

    // Lấy danh sách đánh giá, tổng số, trung bình
    $reviews = Review::with('user')->where('product_id', $product->id)->latest()->get();
    $totalReviews = $reviews->count();
    $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

    // Truyền đủ biến sang view
    return view('client.reviews.review', compact('order', 'product', 'reviews', 'totalReviews', 'averageRating'));
}

public function store(Request $request, $order_id, $product_id)
{
    // Validate dữ liệu đầu vào
    $request->validate([
        'rating'  => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string'
    ]);

    // Kiểm tra lại đơn hàng của user (đảm bảo user sở hữu đơn hàng)
    $order = Order::where('id', $order_id)
        ->where('user_id', auth()->id())
        ->firstOrFail();
    
    // Kiểm tra xem đánh giá cho sản phẩm trong đơn hàng này đã tồn tại hay chưa
    $exists = Review::where('user_id', auth()->id())
        ->where('order_id', $order->id)
        ->where('product_id', $product_id)
        ->exists();

    if ($exists) {
        return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.');
    }

    // Lưu đánh giá vào database với đúng order_id và product_id
    Review::create([
        'user_id'    => auth()->id(),
        'order_id'   => $order->id,
        'product_id' => $product_id,
        'rating'     => $request->rating,
        'comment'    => $request->comment,
    ]);

    return redirect()->route('order-history')
        ->with('success', 'Đánh giá của bạn đã được ghi nhận!');
}

public function show($order_id, $product_id)
{
    $order = Order::find($order_id);
    $product = Products::findOrFail($product_id);
    
    if (!$order) {
        abort(404, "Order not found");
    }

    $reviews = Review::with('user')->where('product_id', $product->id)->latest()->get();
    $averageRating = $reviews->avg('rating'); // rating là cột lưu số sao

    // Đếm tổng số đánh giá
    $totalReviews = $reviews->count();

    return view('client.reviews.review', compact('reviews', 'product', 'product_id', 'order', 'averageRating', 'totalReviews'));
}
}

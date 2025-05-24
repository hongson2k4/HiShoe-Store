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
        $order = Order::where('id', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Chỉ cho phép đánh giá khi đã nhận hàng và chưa đánh giá
        if ($order->status != 7 || $order->is_reviewed == 1) {
            return redirect()->route('order-history')->with('error', 'Chỉ có thể đánh giá khi đã nhận hàng và chưa đánh giá!');
        }

        $product = Products::findOrFail($product_id);

        $reviews = Review::with('user')->where('product_id', $product->id)->latest()->get();
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

        return view('client.reviews.review', compact('order', 'product', 'reviews', 'totalReviews', 'averageRating'));
    }

    public function store(Request $request, $order_id, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $order = Order::where('id', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->status != 7 || $order->is_reviewed == 1) {
            return redirect()->route('order-history')->with('error', 'Chỉ có thể đánh giá khi đã nhận hàng và chưa đánh giá!');
        }

        $exists = Review::where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->where('product_id', $product_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.');
        }

        // dd($order,$exists);

        Review::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'product_id' => $product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Đánh dấu đã đánh giá
        $order->is_reviewed = 1;
        $order->save();

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

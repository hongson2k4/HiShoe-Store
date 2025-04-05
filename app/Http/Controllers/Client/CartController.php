<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product_variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('loginForm');
        }

        $user = Auth::guard('web')->user();
        $cartItems = Cart::with('product', 'variant.size', 'variant.color')
            ->where('user_id', $user->id)
            ->get();

        return view('client.pages.shop.cart', compact('cartItems'));
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);

        if ($request->action == 'increase') {
            $cartItem->quantity += 1;
        } elseif ($request->action == 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
        }

        $cartItem->save();

        return redirect()->route('cart');
    }

    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }

    public function addToCart(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.'], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer',
            'size_id' => 'required|integer',
            'color_id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        $user = Auth::guard('web')->user();
        $variant = Product_variant::where('product_id', $validated['product_id'])
            ->where('size_id', $validated['size_id'])
            ->where('color_id', $validated['color_id'])
            ->first();

        if (!$variant) {
            return response()->json(['success' => false, 'message' => 'Biến thể sản phẩm không tồn tại.'], 404);
        }

        if ($variant->stock_quantity < $validated['quantity']) {
            return response()->json(['success' => false, 'message' => 'Số lượng yêu cầu vượt quá số lượng tồn kho.'], 400);
        }

        try {
            $cartItem = Cart::where([
                'user_id' => $user->id,
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id
            ])->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $validated['quantity'];
                if ($newQuantity > $variant->stock_quantity) {
                    $newQuantity = $variant->stock_quantity;
                }
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                $cartItem = Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $validated['quantity']
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
        } catch (\Exception $e) {
            \Log::error('Error adding to cart: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity']
            ]);
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi khi thêm vào giỏ hàng.'], 500);
        }
    }
}
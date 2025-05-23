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

        // Lấy số lượng đã có trong giỏ hàng
        $cartItem = Cart::where([
            'user_id' => $user->id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id
        ])->first();

        $cartQuantity = $cartItem ? $cartItem->quantity : 0;
        $totalQuantity = $cartQuantity + $validated['quantity'];

        if ($totalQuantity > $variant->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Tổng số lượng trong giỏ hàng vượt quá số lượng tồn kho (' . $variant->stock_quantity . ').'
            ], 400);
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
            \Illuminate\Support\Facades\Log::error('Error adding to cart: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity']
            ]);
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi khi thêm vào giỏ hàng.'], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        $item = Cart::findOrFail($id);
        $variant = $item->variant;

        // Kiểm tra tồn kho trước khi tăng số lượng
        if ($request->action === 'increase') {
            if ($item->quantity + 1 > $variant->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng mua vượt quá số lượng tồn kho (' . $variant->stock_quantity . ').'
                ], 400);
            }
            $item->quantity += 1;
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }

        $item->save();

        // Tổng tiền toàn bộ giỏ hàng (giả sử là của user hiện tại)
        $grandTotal = Cart::all()->sum(fn($i) => $i->variant->price * $i->quantity);

        return response()->json([
            'success' => true,
            'item' => [
                'quantity' => $item->quantity,
                'price' => $item->variant->price,
            ],
            'grandTotal' => $grandTotal
        ]);
    }

}
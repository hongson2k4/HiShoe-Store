<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = [];
        $subtotal = 0;
        $discount = 0;
        $total = 0;
        
        if ($user) {
            // Get cart items for logged in user with brand information
            $cartItems = Cart::where('user_id', $user->id)
                ->with(['productVariant.product.brand', 'productVariant.size', 'productVariant.color'])
                ->get();
        } else {
            // Get cart items from session for guest user
            $sessionCart = Session::get('cart', []);
            
            if (!empty($sessionCart)) {
                foreach ($sessionCart as $item) {
                    $productVariant = ProductVariant::with(['product.brand', 'size', 'color'])
                        ->find($item['product_variant_id']);
                    
                    if ($productVariant) {
                        // Create cart item object similar to database model
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
        
        // Calculate totals
        foreach ($cartItems as $item) {
            $subtotal += $item->productVariant->price * $item->quantity;
        }
        
        // Apply voucher discount if any
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
        
        return view('client.cart', compact('cartItems', 'subtotal', 'discount', 'total'));
    }
    
    /**
     * Add a product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        // Find the product variant
        $productVariant = ProductVariant::where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->first();
            
        if (!$productVariant) {
            return redirect()->back()->with('error', 'Biến thể sản phẩm không tồn tại');
        }
        
        // Check stock
        if ($productVariant->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ');
        }
        
        $user = Auth::user();
        
        if ($user) {
            // Check if product variant already in cart
            $existingCartItem = Cart::where('user_id', $user->id)
                ->where('product_variant_id', $productVariant->id)
                ->first();
                
            if ($existingCartItem) {
                // Update quantity
                $existingCartItem->quantity += $request->quantity;
                $existingCartItem->save();
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => $user->id,
                    'product_variant_id' => $productVariant->id,
                    'quantity' => $request->quantity,
                    'price' => $productVariant->price
                ]);
            }
        } else {
            // Guest user - store in session
            $cart = Session::get('cart', []);
            $cartItemId = uniqid();
            
            // Check if product variant already in session cart
            $existingItemKey = array_search($productVariant->id, array_column($cart, 'product_variant_id'));
            
            if ($existingItemKey !== false) {
                // Update quantity
                $cart[$existingItemKey]['quantity'] += $request->quantity;
            } else {
                // Add new item
                $cart[] = [
                    'id' => $cartItemId,
                    'product_variant_id' => $productVariant->id,
                    'quantity' => $request->quantity,
                    'price' => $productVariant->price
                ];
            }
            
            Session::put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng');
    }
    
    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required',
            'action' => 'required|in:increase,decrease'
        ]);
        
        $user = Auth::user();
        
        if ($user) {
            $cartItem = Cart::where('id', $request->cart_id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm trong giỏ hàng'
                ]);
            }
            
            // Get product variant to check stock
            $productVariant = ProductVariant::find($cartItem->product_variant_id);
            
            if ($request->action == 'increase') {
                // Check stock before increasing
                if ($cartItem->quantity >= $productVariant->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Đã đạt số lượng tối đa có thể mua'
                    ]);
                }
                
                $cartItem->quantity += 1;
            } else {
                if ($cartItem->quantity > 1) {
                    $cartItem->quantity -= 1;
                } else {
                    // Remove item if quantity becomes 0
                    $cartItem->delete();
                    return response()->json(['success' => true]);
                }
            }
            
            $cartItem->save();
        } else {
            // Update session cart for guest user
            $cart = Session::get('cart', []);
            
            foreach ($cart as $key => $item) {
                if ($item['id'] == $request->cart_id) {
                    if ($request->action == 'increase') {
                        // Get product variant to check stock
                        $productVariant = ProductVariant::find($item['product_variant_id']);
                        
                        if ($item['quantity'] >= $productVariant->stock_quantity) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Đã đạt số lượng tối đa có thể mua'
                            ]);
                        }
                        
                        $cart[$key]['quantity'] += 1;
                    } else {
                        if ($item['quantity'] > 1) {
                            $cart[$key]['quantity'] -= 1;
                        } else {
                            // Remove item if quantity becomes 0
                            unset($cart[$key]);
                            $cart = array_values($cart);
                        }
                    }
                    
                    Session::put('cart', $cart);
                    break;
                }
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required'
        ]);
        
        $user = Auth::user();
        
        if ($user) {
            Cart::where('id', $request->cart_id)
                ->where('user_id', $user->id)
                ->delete();
        } else {
            $cart = Session::get('cart', []);
            
            foreach ($cart as $key => $item) {
                if ($item['id'] == $request->cart_id) {
                    unset($cart[$key]);
                    break;
                }
            }
            
            Session::put('cart', array_values($cart));
        }
        
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng');
    }
    
    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        $user = Auth::user();
        
        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            Session::forget('cart');
        }
        
        Session::forget('applied_voucher');
        
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được xóa');
    }
    
    /**
     * Apply voucher to cart
     */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string'
        ]);
        
        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
            
        if (!$voucher) {
            return redirect()->route('cart.index')->with('voucher_error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn');
        }
        
        // Store voucher in session
        Session::put('applied_voucher', $voucher->code);
        
        return redirect()->route('cart.index')->with('voucher_success', 'Áp dụng mã giảm giá thành công');
    }
}
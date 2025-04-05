<?php
namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\Like;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LikeController extends Controller {
    
    // Xử lý toggle Like (Thích/Bỏ thích)
    public function toggleLike(Request $request, $id)
{
    if (!auth()->guard('web')->check()) {
        return response()->json(['error' => 'Bạn phải đăng nhập để thích sản phẩm!'], 403);
    }

    $user = auth()->guard('web')->user();
    $like = Like::where('user_id', $user->id)->where('product_id', $id)->first();

    if ($like) {
        $like->delete();
        return response()->json(['liked' => false]);
    } else {
        Like::create([
            'user_id' => $user->id,
            'product_id' => $id
        ]);
        return response()->json(['liked' => true]);
    }
}


    // Hiển thị danh sách sản phẩm đã thích
    public function likedProducts()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('loginForm')->with('error', 'Bạn cần đăng nhập để xem sản phẩm đã thích.'); // Correct route name
        }

        $likedProducts = Products::whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('client.pages.likes.liked_products', compact('likedProducts'));
    }
}

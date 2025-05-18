<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $products = Products::all(); // Lấy danh sách sản phẩm
        return view('client.home', compact('products')); // Truyền biến $products vào view
    }

    public function test()
    {
        if(!Auth::guard('web')->check()){
            return redirect()->route('loginForm')->with('error', 'Chức năng này yêu cầu đăng nhập.');
        }
        return view('client.pages.user.add_address');
    }
}
<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $products = \App\Models\Products::whereHas('category', function($q) {
                $q->where('status', 0);
            })
            ->whereHas('brand', function($q) {
                $q->where('status', 1);
            })
            ->get();
        $vouchers = \App\Models\Voucher::active()->get();
        return view('client.home', compact('products', 'vouchers'));
    }

    public function test()
    {
        if(!Auth::guard('web')->check()){
            return redirect()->route('loginForm')->with('error', 'Chức năng này yêu cầu đăng nhập.');
        }
        return view('client.pages.user.add_address');
    }
}

<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Products;

class HomeController extends Controller
{
    public function home()
    {
        $products = Products::all(); // Lấy danh sách sản phẩm
        return view('client.home', compact('products')); // Truyền biến $products vào view
    }
}
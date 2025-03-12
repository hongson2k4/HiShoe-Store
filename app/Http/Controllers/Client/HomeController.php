<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;


class HomeController extends Controller
{
    public function index()
    {
        // Lấy sản phẩm mới nhất
        $product = Product::latest()->first();

        if ($product) {
            // Lấy các ID size và color có trong biến thể của sản phẩm
            $variantSizes = $product->productVariants()->pluck('size_id')->unique();
            $variantColors = $product->productVariants()->pluck('color_id')->unique();

            // Lấy thông tin chi tiết từ bảng sizes và colors theo các ID vừa lấy
            $sizes = Size::whereIn('id', $variantSizes)->get();
            $colors = Color::whereIn('id', $variantColors)->get();
        } else {
            // Nếu không có sản phẩm, trả về rỗng
            $sizes = collect();
            $colors = collect();
        }

        // Truyền dữ liệu sang view home
        return view('client.home', compact('product', 'sizes', 'colors'));
    }
}


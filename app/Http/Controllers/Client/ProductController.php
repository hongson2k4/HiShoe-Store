<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Products;
use App\Models\Product_variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        $products = $query->get();
        $categories = Category::all();
        $brands = Brand::all();
        $maxPrice = Products::max('price');

        return view('client.pages.shop.shop', compact('products', 'categories', 'brands', 'maxPrice'));
    }

    public function detail($product_id)
    {
        $product = Products::findOrFail($product_id);
        $variants = $product->variants;
    
        // Lấy danh sách các kích cỡ và màu sắc có sẵn cho sản phẩm này
        $availableSizes = $variants->pluck('size.name', 'size.id')->unique()->toArray();
        $availableColors = $variants->pluck('color.name', 'color.id')->unique()->toArray();
    
        return view('client.pages.shop.detail', compact('product', 'variants', 'availableSizes', 'availableColors'));
    }
    
    public function getVariantPrice(Request $request)
    {
        $product_id = $request->product_id;
        $size_id = $request->size_id;
        $color_id = $request->color_id;
        
        $variant = Product_variant::where('product_id', $product_id)
            ->when($size_id, function ($query) use ($size_id) {
                return $query->where('size_id', $size_id);
            })
            ->when($color_id, function ($query) use ($color_id) {
                return $query->where('color_id', $color_id);
            })
            ->first();
        
        return response()->json(['price' => $variant ? $variant->price : null]);
    }
}

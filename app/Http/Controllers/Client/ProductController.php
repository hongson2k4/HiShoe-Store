<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product; // Sửa từ Products thành Product
use App\Models\ProductVariant; // Sửa từ Product_variant thành ProductVariant
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
    
        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->whereIn('category_id', (array) $request->category_id);
        }
    
        // Lọc theo nhãn hàng
        if ($request->filled('brand_id')) {
            $query->whereIn('brand_id', (array) $request->brand_id);
        }
    
        // Lọc theo khoảng giá
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }
    
        $products = $query->get();
        $categories = Category::all();
        $brands = Brand::all();
        $maxPrice = Product::max('price');
    
        // Trả về view với các bộ lọc đã chọn
        return view('client.pages.shop.shop', compact('products', 'categories', 'brands', 'maxPrice'))
            ->with('selectedFilters', $request->all());
    }

    public function detail($product_id)
    {
        $product = Product::findOrFail($product_id);
        $variants = $product->variants;
    
        // Lấy danh sách các kích cỡ và màu sắc có sẵn cho sản phẩm này
        $availableSizes = $variants->pluck('size.name', 'size.id')->unique()->toArray();
        $availableColors = $variants->pluck('color.name', 'color.id')->unique()->toArray();
    
        // Lấy danh sách sản phẩm gợi ý theo danh mục
        $suggestedProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $product_id)
                                    ->take(8)
                                    ->get();
    
        return view('client.pages.shop.detail', compact(
            'product',
            'variants',
            'availableSizes',
            'availableColors',
            'suggestedProducts'
        ));
    }
    
    public function getVariantPrice(Request $request)
    {
        $product_id = $request->product_id;
        $size_id = $request->size_id;
        $color_id = $request->color_id;
        
        $variant = ProductVariant::where('product_id', $product_id)
            ->when($size_id, function ($query) use ($size_id) {
                return $query->where('size_id', $size_id);
            })
            ->when($color_id, function ($query) use ($color_id) {
                return $query->where('color_id', $color_id);
            })
            ->first();
        
        return response()->json(['price' => $variant ? $variant->price : null]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $variants = $product->variants;

        // Lấy danh sách các kích cỡ và màu sắc có sẵn cho sản phẩm này
        $availableSizes = $variants->pluck('size.name', 'size.id')->unique()->toArray();
        $availableColors = $variants->pluck('color.name', 'color.id')->unique()->toArray();

        // Lấy danh sách sản phẩm gợi ý theo danh mục
        $suggestedProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $id)
                                    ->take(8)
                                    ->get();

        return view('client.pages.shop.detail', compact(
            'product',
            'variants',
            'availableSizes',
            'availableColors',
            'suggestedProducts'
        ));
    }
}
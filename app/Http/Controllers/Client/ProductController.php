<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Products;
use App\Models\Product_variant;
use App\Models\Size; // Thêm dòng này để sử dụng model Size
use App\Models\Color;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::query();
    
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
    
        // Lọc theo kích thước
        if ($request->filled('size_id')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size_id', $request->size_id);
            });
        }
    
        // Lọc theo màu sắc
        if ($request->filled('color_id')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color_id', $request->color_id);
            });
        }
    
        $products = $query->get();
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all(); // Lấy danh sách kích thước
        $colors = Color::all(); // Lấy danh sách màu sắc
        $maxPrice = Products::max('price');
    
        // Trả về view với các bộ lọc đã chọn
        return view('client.pages.shop.shop', compact('products', 'categories', 'brands', 'sizes', 'colors', 'maxPrice'))
            ->with('selectedFilters', $request->all());
    }

    public function detail($product_id)
    {
        $products = Products::findOrFail($product_id);
        // dd($product->id);
        $variants = $products->variants;

        // Lấy danh sách các kích cỡ và màu sắc có sẵn cho sản phẩm này
        $availableSizes = $variants->pluck('size.name', 'size.id')->unique()->toArray();
        $availableColors = $variants->pluck('color.name', 'color.id')->unique()->toArray();

        // Lấy danh sách sản phẩm gợi ý theo danh mục
        $suggestedProducts = Products::where('category_id', $products->category_id)
            ->where('id', '!=', $product_id)
            ->take(8)
            ->get();

        return view('client.pages.shop.detail', compact(
            'products',
            'variants',
            'availableSizes',
            'availableColors',
            'suggestedProducts'
        ));
    }

    public function getVariantPrice(Request $request)
    {
        \Log::info('Request data:', $request->all());
        $product = $request->product_id;
        // echo($product->id);
        $size_id = $request->size_id;
        $color_id = $request->color_id;

        \Log::info("Fetching variant price for product_id: $product, size_id: $size_id, color_id: $color_id");

        $variant = Product_variant::where('product_id', $product)
            ->when($size_id, function ($query) use ($size_id) {
                return $query->where('size_id', $size_id);
            })
            ->when($color_id, function ($query) use ($color_id) {
                return $query->where('color_id', $color_id);
            })
            ->first();
        if (!$variant) {
            \Log::warning("Variant not found for product_id: $product, size_id: $size_id, color_id: $color_id");
            return response()->json(['price' => null, 'error' => 'Variant not found'], 404);
        }

        return response()->json(['price' => $variant->price]);
    }

    public function show($id)
    {
        $product = Products::findOrFail($id);
        $suggestedProducts = Products::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(8)
            ->get();

        return view('client.pages.shop.detail', [
            'product' => $product,
            'suggestedProducts' => $suggestedProducts,
            'availableSizes' => $product->sizes,
            'availableColors' => $product->colors,
            'variants' => $product->variants,
        ]);
    }
    public function category($category_id)
    {
        $category = Category::findOrFail($category_id);
        $products = Products::where('category_id', $category_id)->get();

        return view('client.pages.shop.category', compact('category', 'products'));
    }

    public function brand($brand_id)
    {
        $brand = Brand::findOrFail($brand_id);
        $products = Products::where('brand_id', $brand_id)->get();

        return view('client.pages.shop.brand', compact('brand', 'products'));
    }
}

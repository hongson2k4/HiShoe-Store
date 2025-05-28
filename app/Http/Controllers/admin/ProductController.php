<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    private $view;

    public function __construct(){
        $this->view = [];
    }
    public function index(Request $request)
    {
        $search = $request->query('search');

        $brands = DB::table('brands')->get();
        $categories = DB::table('categories')->get();
        $products = Products::query()
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%");
            });
        })
        ->with('category')
        ->with('brand')
        ->get();
        return view("admin.products.list", compact("products"));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $products = Products::where(function($query) use ($search){
            $query->where('name','like',"%$search%")
            ->orWhere('description','like',"%$search%")
            ->orWhere('price','like',"%$search%");
        })
        ->orWhereHas('brands',function($query) use ($search){
            $query->where('brand_id','like',"%$search%");
        })
        ->get();
        return view("admin.products.list", compact("products","search"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = DB::table('brands')->get();
        $categories = DB::table('categories')->get();
        // Use old('sku_code') if available, otherwise generate a new one
        $skuCode = old('sku_code') ?? (new Products())->generateSku();
        return view("admin.products.create", compact('categories', 'brands', 'skuCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'image_url' => 'nullable',
                'sku_code' => 'required|string|unique:products,sku_code',
                'description_title' => 'required|string|max:255',
                'description_content' => 'required|string',
                'description_image' => 'nullable',
                // Validate biến thể
                'variants' => 'required|array|min:1',
                'variants.*.size_id' => 'required|exists:sizes,id',
                'variants.*.color_id' => 'required|exists:colors,id',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.stock_quantity' => 'required|integer|min:0',
                'variants.*.image' => 'nullable|image',
            ], [
                'name.required' => 'Tên sản phẩm là bắt buộc.',
                'price.required' => 'Giá sản phẩm là bắt buộc.',
                'price.numeric' => 'Giá sản phẩm phải là số.',
                'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
                'category_id.exists' => 'Danh mục không tồn tại.',
                'brand_id.required' => 'Thương hiệu sản phẩm là bắt buộc.',
                'brand_id.exists' => 'Thương hiệu không tồn tại.',
                'sku_code.required' => 'Mã SKU là bắt buộc.',
                'sku_code.unique' => 'Mã SKU đã tồn tại.',
                'description_title.required' => 'Tiêu đề mô tả là bắt buộc.',
                'description_content.required' => 'Nội dung mô tả là bắt buộc.',
                'variants.required' => 'Phải có ít nhất một biến thể.',
                'variants.*.size_id.required' => 'Kích thước là bắt buộc.',
                'variants.*.size_id.exists' => 'Kích thước không hợp lệ.',
                'variants.*.color_id.required' => 'Màu sắc là bắt buộc.',
                'variants.*.color_id.exists' => 'Màu sắc không hợp lệ.',
                'variants.*.price.required' => 'Giá biến thể là bắt buộc.',
                'variants.*.price.numeric' => 'Giá biến thể phải là số.',
                'variants.*.stock_quantity.required' => 'Số lượng biến thể là bắt buộc.',
                'variants.*.stock_quantity.integer' => 'Số lượng biến thể phải là số nguyên.',
                'variants.*.image.image' => 'Ảnh biến thể phải là định dạng ảnh.',
            ]);

            // Custom validate: ít nhất 1 biến thể
            if (!isset($request->variants) || count($request->variants) < 1) {
                return redirect()->back()->withErrors(['Phải có ít nhất một biến thể.'])->withInput();
            }

            // Custom validate: giá biến thể >= 50% giá sản phẩm
            $productPrice = $validate['price'];
            foreach ($request->variants as $variant) {
                if (!isset($variant['price']) || $variant['price'] < 0.5 * $productPrice) {
                    return redirect()->back()->withErrors(['Giá biến thể phải lớn hơn hoặc bằng 50% giá sản phẩm.'])->withInput();
                }
            }

            $imagePath = null;
            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('products', 'public');
            }

            // Tính tổng số lượng từ biến thể
            $totalStock = 0;
            foreach ($request->variants as $variant) {
                $totalStock += (int)($variant['stock_quantity'] ?? 0);
            }

            $product = Products::create([
                'name' => $validate['name'],
                'price' => $validate['price'],
                'brand_id' => $validate['brand_id'],
                'category_id' => $validate['category_id'],
                'sku_code' => $validate['sku_code'],
                'image_url' => $imagePath,
                'stock_quantity' => $totalStock,
                // Không lưu return_policy
            ]);

            // Lưu mô tả sản phẩm
            $descriptionImagePath = null;
            if ($request->hasFile('description_image')) {
                $descriptionImagePath = $request->file('description_image')->store('product_details', 'public');
            }

            $product->productDetails()->create([
                'detail_title' => $validate['description_title'],
                'detail_content' => $validate['description_content'],
                'detail_image' => $descriptionImagePath,
            ]);

            // Lưu các biến thể
            foreach ($request->variants as $variant) {
                $variantImagePath = null;
                if (isset($variant['image']) && $variant['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantImagePath = $variant['image']->store('product_variants', 'public');
                }
                $product->variants()->create([
                    'size_id' => $variant['size_id'],
                    'color_id' => $variant['color_id'],
                    'price' => $variant['price'],
                    'stock_quantity' => $variant['stock_quantity'],
                    'image_url' => $variantImagePath,
                ]);
            }
            $product->syncStockQuantity();
            return redirect()->route('products.list')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brands = DB::table('brands')->get();
        $categories = DB::table('categories')->get();
        $product = Products::find($id);
        // dd($product);
        return view('admin.products.edit', compact('product','categories','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(Request $request, string $id)
    {
        $validate = $request->validate([
            'name'=> 'required',
            'description' => 'required',
            'price'=> 'required',
            'stock_quantity'=>'required',
            'category_id'=>'required',
            'brand_id'=>'required',
            'image_url'=>'nullable',
        ], [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'description.required' => 'Mô tả sản phẩm là bắt buộc.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'stock_quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'brand_id.required' => 'Thương hiệu sản phẩm là bắt buộc.',
        ]);

        $product = Products::find($id);

        if($request->hasFile('image_url')){
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }
            $part = $request->file('image_url')->store('uploads/image_url','public');
        }else{
            $part = $product->image_url;
        }

        $product->update([
            'name'=>$validate['name'],
            'description'=>$validate['description'],
            'price'=>$validate['price'],
            'stock_quantity'=>$validate['stock_quantity'],
            'category_id'=>$validate['category_id'],
            'brand_id'=>$validate['brand_id'],
            'image_url'=>$part,
        ]);
        return redirect()->route('products.list')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProduct($id)
    {
        Products::findOrFail($id)->delete();
        return redirect()->route('products.list');
    }

    /**
     * Hide the specified resource.
     */
    public function hide($id)
    {
        $product = Products::findOrFail($id);
        $product->delete(); // Soft delete
        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được ẩn!');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $product = Products::withTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('products.list')->with('success', 'Sản phẩm đã được khôi phục!');
    }

    /**
     * Display a listing of the hidden resources.
     */
    public function hidden()
    {
        $products = Products::onlyTrashed()->with('category', 'brand')->get();
        return view('admin.products.hidden', compact('products'));
    }
}

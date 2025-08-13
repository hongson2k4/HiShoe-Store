<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product_variant;
use App\Models\Products;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $product_id)
    {
        $product = Products::findOrFail($product_id);
        $products_variant = Product_variant::where('product_id', $product_id)
            ->with(['color', 'size', 'product'])
            ->get();
        return view("admin.products_variant.list", compact("products_variant", "product_id", "product"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($product_id)
    {
        $product = Products::findOrFail($product_id);
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products_variant.create', compact('product_id', 'sizes', 'colors', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable',
        ], [
            'size_id.required' => 'Vui lòng chọn kích thước!',
            'size_id.exists' => 'Kích thước không hợp lệ!',
            'color_id.required' => 'Vui lòng chọn màu sắc!',
            'color_id.exists' => 'Màu sắc không hợp lệ!',
            'price.required' => 'Vui lòng nhập giá!',
            'price.numeric' => 'Giá phải là số!',
            'stock_quantity.required' => 'Vui lòng nhập số lượng!',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên!',
            'image.image' => 'Tệp phải là hình ảnh!',
            'image.max' => 'Dung lượng ảnh không được vượt quá 2MB!',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_variants', 'public');
        }

        Product_variant::create([
            'product_id' => $product_id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imagePath,
        ]);

        // Đồng bộ lại số lượng kho tổng
        $product = Products::find($product_id);
        if ($product) {
            $product->syncStockQuantity();
        }

        return redirect()->route('products.variant.list', $product_id)->with('success', 'Biến thể sản phẩm đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product_variant = Product_variant::with(['color', 'size', 'product'])->findOrFail($id);
        return view("admin.products_variant.show", compact("product_variant"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product_id, $id)
    {
        $product_variant = Product_variant::findOrFail($id);
        $products = Products::find($product_variant->product_id);
        $colors = Color::all();
        $sizes = Size::all();
        // dd($product_variant, $products, $colors, $sizes);
        return view("admin.products_variant.edit", compact('product_variant', 'products', 'colors', 'sizes', 'product_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $product_id, string $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer',
            'image_url' => 'nullable|image|max:2048',
        ], [
            'stock_quantity.required' => 'Vui lòng nhập số lượng!',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên!',
            'image_url.image' => 'Tệp phải là hình ảnh!',
            'image_url.max' => 'Dung lượng ảnh không được vượt quá 2MB!',
        ]);

        $product_variant = Product_variant::findOrFail($id);

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('product_variants', 'public');
            $product_variant->image_url = $imagePath;
        }

        $product_variant->stock_quantity = $request->stock_quantity;
        $product_variant->save();

        // Đồng bộ lại số lượng kho tổng
        $product = $product_variant->product;
        if ($product) {
            $product->syncStockQuantity();
        }

        return redirect()->route('products.variant.list', ['product_id' => $product_variant->product_id])
            ->with('success', 'Cập nhật biến thể sản phẩm thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id, $id)
    {
        $product_variant = Product_variant::findOrFail($id);

        if ($product_variant->stock_quantity > 0) {
            return redirect()->route('products.variant.list', ['product_id' => $product_variant->product_id])
                ->with('error', 'Không thể xóa biến thể sản phẩm vì số lượng tồn kho lớn hơn 0!');
        }

        $product_id = $product_variant->product_id;
        $product_variant->delete();

        // Đồng bộ lại số lượng kho tổng
        $product = Products::find($product_id);
        if ($product) {
            $product->syncStockQuantity();
        }

        return redirect()->route('products.variant.list', ['product_id' => $product_id])
            ->with('success', 'Xóa biến thể sản phẩm thành công!');
    }
}
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
        $products_variant = Product_variant::where('product_id', $product_id)
            ->with(['color', 'size', 'product'])
            ->get();
        return view("admin.products_variant.list", compact("products_variant", "product_id"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($product_id)
    {
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products_variant.create', compact('product_id', 'sizes', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ], [
            'size_id.required' => 'Vui lòng chọn kích thước!',
            'size_id.exists' => 'Kích thước không hợp lệ!',
            'color_id.required' => 'Vui lòng chọn màu sắc!',
            'color_id.exists' => 'Màu sắc không hợp lệ!',
            'price.required' => 'Vui lòng nhập giá!',
            'price.numeric' => 'Giá phải là số!',
            'stock_quantity.required' => 'Vui lòng nhập số lượng!',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên!',
        ]);

        Product_variant::create([
            'product_id' => $product_id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
        ]);

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
    public function edit(string $id)
    {
        $product_variant = Product_variant::findOrFail($id);
        $products = Products::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view("admin.products_variant.edit", compact('product_variant', 'products', 'colors', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|integer',
            'stock_quantity' => 'required|integer',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm!',
            'product_id.exists' => 'Sản phẩm không hợp lệ!',
            'size_id.required' => 'Vui lòng chọn kích thước!',
            'size_id.exists' => 'Kích thước không hợp lệ!',
            'color_id.required' => 'Vui lòng chọn màu sắc!',
            'color_id.exists' => 'Màu sắc không hợp lệ!',
            'price.required' => 'Vui lòng nhập giá!',
            'price.integer' => 'Giá phải là số nguyên!',
            'stock_quantity.required' => 'Vui lòng nhập số lượng!',
            'stock_quantity.integer' => 'Số lượng phải là số nguyên!',
        ]);

        $product_variant = Product_variant::findOrFail($id);
        $product_variant->update($request->all());

        return redirect()->route('products.variant.list', ['product_id' => $product_variant->product_id])->with('success', 'Cập nhật biến thể sản phẩm thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product_variant = Product_variant::findOrFail($id);
        $product_id = $product_variant->product_id;
        $product_variant->delete();
        return redirect()->route('products.variant.list', ['product_id' => $product_id])->with('success', 'Xóa biến thể sản phẩm thành công!');
    }
}
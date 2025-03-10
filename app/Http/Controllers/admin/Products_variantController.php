<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Products_variant;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Facades\DB;

class Products_variantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products_variant = Products_variant::with(['color', 'size','brand','category'])->get();
        $colors = DB::table('colors')->get();
        $sizes = DB::table('sizes')->get();
        $brands = DB::table('brands')->get();
        $categories = DB::table('categories')->get();
    
        return view("admin.products_variant.list", compact("products_variant"));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Products::all(); // Lấy tất cả sản phẩm từ bảng products
        return view("admin.products_variant.create", compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validate = $request->validate([
        //     'product_id' => 'required',
        // ]);
    
        // Products_variant::create([
        //     'product_id' => $validate['product_id'],
        // ]);
    
        // return redirect()->route('admin.products_variant.list')->with('success', 'Thêm biến thể thành công!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

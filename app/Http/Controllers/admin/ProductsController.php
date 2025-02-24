<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Facades\DB;


class ProductsController extends Products
{
    private $view;

    public function __construct(){
        $this->view = [];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        // dd($users);
        return view("admin.products.list", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = DB::table('brands')->get();
        $categories = DB::table('categories')->get();
        return view("admin.products.create", compact('categories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'=> 'required',
            'description' => 'required',
            'price'=> 'required',
            'stock_quantity'=>'required',
            'category_id'=>'required',
            'brand_id'=>'required',
            'image_url'=>'nullable|file|mimes:jpg,jpeg,png',

        ]);
        if($request->hasFile('image_url')){
            $part = $request->file('image_url')->store('uploads/image_url','public');
        }else{
            $part = null;
        };
        $product = Products::create([
            'name'=>$validate['name'],
            'description'=>$validate['description'],
            'price'=>$validate['price'],
            'stock_quantity'=>$validate['stock_quantity'],
            'category_id'=>$validate['category_id'],
            'brand_id'=>$validate['brand_id'],
            'image_url'=>$part,
        ]);
        return redirect()->route('products.list');
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
            'image_url'=>'nullable|file|mimes:jpg,jpeg,png',
        ]);
        $product = Products::find($id);
    
        if($request->hasFile('image_url')){
            if($product->image_url){
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
        return redirect()->route('products.list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProduct($id)
    {
        Products::findOrFail($id)->delete();
        return redirect()->route('products.list');
    }

}

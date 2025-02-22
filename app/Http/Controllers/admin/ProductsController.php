<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// class BrandController extends Brand
// {
//     public function create(){
//         $brands = DB::table('brands')
//         ->select('brands.*', 'id')
//         ->orderByDesc('id')
//         ->get();
//     }
// }

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
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $user = Users::find($id);
    //     // dd($user);
    //     return view('admin.users.edit', compact('user'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $validate = $request->validate([
    //         'username'=>'required',
    //         'password'=> 'required',
    //         'full_name' => 'required',
    //         'email'=> 'required',
    //         'avatar'=>'nullable|file|mimes:jpg,jpeg,png',
    //         'phone_number'=>'required',
    //         'address'=>'required',

    //     ]);
    //     $user = Users::find($id);
    //     if($request->hasFile('avatar')){
    //         if($user->avatar){
    //             Storage::disk('public')->delete($user->avatar);
    //         }
    //         $part = $request->file('avatar')->store('uploads/users','public');
    //     }else{
    //         $part = $user->avatar;
    //     }
    //     $user = Users::create([
    //         'username'=>$validate['username'],
    //         'password'=>$validate['password'],
    //         'full_name'=>$validate['full_name'],
    //         'email'=>$validate['email'],
    //         'avatar'=>$part,
    //         'phone_number'=>$validate['phone_number'],
    //         'address'=>$validate['address'],
    //         'role'=>0,
    //     ]);
    //     return redirect()->route('users.list');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //      $products = Products::findOrFail($id)->delete();
    //      return redirect()->route('products.list');
    // }

    // public function ban(string $id)
    // {

    // }
}

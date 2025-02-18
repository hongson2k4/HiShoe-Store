<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(){
        $brands = Brand::latest('id')->paginate(7);
        return view('admin.brands.index',compact('brands'));
    }
    public function create(){
        return view('admin.brands.create');
    }
    public function store(Request $request){
        $data= $request->all();
        Brand::query()->create($data);
        return redirect()->route('brands.list');
    }
    public function delete($id){
        $brands= Brand::findOrFail($id)->delete();
        return redirect()->route('brands.list');
    }
    public function edit($id){
        $brands= Brand::findOrFail($id);
        return view('admin.brands.edit',compact('brands'));
    }
    public function update(Request $request,$id){
        $data= $request->all();
        $brand = Brand::findOrFail($id);
        $brand->update($data);
        return redirect()->route('brands.list');
    }
}

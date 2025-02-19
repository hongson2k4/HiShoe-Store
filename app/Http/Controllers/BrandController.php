<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request\validatebr;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request){
        $query = Brand::query();
        if($request->has('search')){
            $search = $request->input('search');
            $query->where('name','like',"%{$search}%");
        }
        $brands = $query->latest('id')->paginate(7);

        return view('admin.brands.index',compact('brands'));
    }
    public function create(){
        return view('admin.brands.create');
    }
    public function store(validatebr $request){
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

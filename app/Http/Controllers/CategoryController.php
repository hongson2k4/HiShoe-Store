<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $category = Category::latest('id')->paginate(7);
        return view('admin.categories.index',compact('category'));
    }
    public function create(){
        return view('admin.categories.create');
    }
    public function store(Request $request){
        $data= $request->all();
        Category::query()->create($data);
        return redirect()->route('category.list');
    }
    public function delete($id){
        $brands= Category::findOrFail($id)->delete();
        return redirect()->route('category.list');
    }
    public function edit($id){
        $category= Category::findOrFail($id);
        return view('admin.categories.edit',compact('category'));
    }
    public function update(Request $request,$id){
        $data= $request->all();
        $category = Category::findOrFail($id);
        $category->update($data);
        return redirect()->route('category.list');
    }
}

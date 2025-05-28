<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request){
    $query = Category::query();

    if ($request->has('search') && $request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $category = $query->latest('id')->paginate(7);
    return view('admin.categories.index', compact('category'));
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
        $category = Category::findOrFail($id);
        $category->status = 1; // Ẩn thay vì xóa
        $category->save();
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
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = $category->status == 0 ? 1 : 0;
        $category->save();
        return redirect()->route('category.list');
    }
}

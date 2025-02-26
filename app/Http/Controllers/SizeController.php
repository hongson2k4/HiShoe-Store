<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;    

class SizeController extends Controller
{
    public function index() {
        $sizes = Size::all();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create() {
        return view('admin.sizes.create');
    }

    public function store(Request $request) {
        $request->validate(['size' => 'required|unique:sizes']);
        Size::create($request->all());
        return redirect()->route('sizes.index')->with('success', 'Size đã thêm.');
    }

    public function edit(Size $size) {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size) {
        $request->validate(['size' => 'required|unique:sizes,size,' . $size->id]);
        $size->update($request->all());
        return redirect()->route('sizes.index')->with('success', 'Size đã cập nhật.');
    }

    public function destroy(Size $size) {
        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'Size đã xóa.');
    }
}



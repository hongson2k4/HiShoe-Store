<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index() {
        $colors = Color::all();
        return view('admin.colors.index', compact('colors'));
    }

    public function create() {
        return view('admin.colors.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:colors', 'code' => 'nullable|string']);
        Color::create($request->all());
        return redirect()->route('colors.index')->with('success', 'Màu đã thêm.');
    }

    public function edit(Color $color) {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color) {
        $request->validate(['name' => 'required|unique:colors,name,' . $color->id, 'code' => 'nullable|string']);
        $color->update($request->all());
        return redirect()->route('colors.index')->with('success', 'Màu đã cập nhật.');
    }

    public function destroy(Color $color) {
        $color->delete();
        return redirect()->route('colors.index')->with('success', 'Màu đã xóa.');
    }
}


<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:colors',
            'code' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên màu!',
            'name.unique' => 'Tên màu này đã tồn tại!',
            'code.string' => 'Mã màu phải là chuỗi!',
        ]);

        Color::create($request->all());

        return redirect()->route('colors.index')->with('success', 'Màu đã được thêm.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|unique:colors,name,' . $id,
            'code' => 'nullable|string|max:10',
        ], [
            'name.required' => 'Vui lòng nhập tên màu!',
            'name.string' => 'Tên màu phải là chuỗi ký tự!',
            'name.unique' => 'Tên màu này đã tồn tại!',
            'code.string' => 'Mã màu phải là chuỗi!',
            'code.max' => 'Mã màu không được vượt quá 10 ký tự!',
        ]);

        // Cập nhật dữ liệu
        $color->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('colors.index')->with('success', 'Màu đã cập nhật thành công!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('colors.index')->with('success', 'Màu đã xóa.');
    }
}


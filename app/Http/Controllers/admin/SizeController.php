<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        // Kiểm tra xem size đã tồn tại chưa
        $request->validate([
            'name' => 'required|integer|unique:sizes,name',
        ], [
            'name.required' => 'Vui lòng nhập kích thước!',
            'name.integer' => 'Kích thước phải là số nguyên!',
            'name.unique' => 'Kích thước này đã tồn tại!',
        ]);

        // Thêm size mới
        Size::create([
            'name' => $request->name
        ]);

        return redirect()->route('sizes.index')->with('success', 'Size đã thêm thành công!');
    }


    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    // Xử lý cập nhật size
    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|integer|unique:sizes,name,' . $id,
        ], [
            'name.required' => 'Vui lòng nhập kích thước!',
            'name.integer' => 'Kích thước phải là số nguyên!',
            'name.unique' => 'Kích thước này đã tồn tại!',
        ]);

        // Cập nhật dữ liệu
        $size->update([
            'name' => $request->name
        ]);

        return redirect()->route('sizes.index')->with('success', 'Size đã cập nhật thành công!');
    }

    public function destroy(Size $size)
    {
        // Kiểm tra nếu size đang được liên kết với bảng khác
        if ($size->products()->exists()) { // Giả sử bảng products có cột size_id
            return redirect()->route('sizes.index')->with('error', 'Không thể xóa kích thước vì đang được sử dụng.');
        }

        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'Size đã xóa.');
    }
}



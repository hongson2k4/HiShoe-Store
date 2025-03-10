<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
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
        // Kiểm tra xem size đã tồn tại chưa
        $request->validate([
            'size' => 'required|integer|unique:sizes,size',
        ], [
            'size.required' => 'Vui lòng nhập kích thước!',
            'size.integer' => 'Kích thước phải là số nguyên!',
            'size.unique' => 'Kích thước này đã tồn tại!',
        ]);
    
        // Thêm size mới
        Size::create([
            'size' => $request->size
        ]);
    
        return redirect()->route('sizes.index')->with('success', 'Size đã thêm thành công!');
    }
    

    public function edit(Size $size) {
        return view('admin.sizes.edit', compact('size'));
    }

   // Xử lý cập nhật size
   public function update(Request $request, $id) {
    $size = Size::findOrFail($id);

    // Kiểm tra dữ liệu đầu vào
    $request->validate([
        'size' => 'required|integer|unique:sizes,size,' . $id,
    ], [
        'size.required' => 'Vui lòng nhập kích thước!',
        'size.integer' => 'Kích thước phải là số nguyên!',
        'size.unique' => 'Kích thước này đã tồn tại!',
    ]);

    // Cập nhật dữ liệu
    $size->update([
        'size' => $request->size
    ]);

    return redirect()->route('sizes.index')->with('success', 'Size đã cập nhật thành công!');
}


    public function destroy(Size $size) {
        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'Size đã xóa.');
    }
}



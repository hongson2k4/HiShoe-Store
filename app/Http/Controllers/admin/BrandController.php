<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    // Hiển thị danh sách brand + tìm kiếm + lọc
    public function index(Request $request)
    {
        $brands = Brand::when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . trim($request->search) . '%');
        })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderByDesc('updated_at')
            ->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }


    // Lưu brand mới
    public function store(Request $request)
    {
        Brand::create($request->only(['name', 'description']) + ['status' => 1]);

        return redirect()->route('brands.index')->with('success', 'Brand added successfully!');
    }

    // Chỉnh sửa brand
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    // Cập nhật brand
    public function update(Request $request, Brand $brand)
    {
        $brand->update($request->only(['name', 'description']));

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
    }

    // Ẩn/hiện brand
    public function toggleStatus(Brand $brand)
    {
        $brand->update(['status' => !$brand->status]);

        return redirect()->route('brands.index')->with('success', 'Brand status updated successfully.');
    }
}

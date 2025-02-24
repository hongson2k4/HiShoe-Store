<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.list', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Brand::create($request->all());
        return redirect()->route('brands.index')->with('success', 'Brand added successfully!');
    }
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

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $brand->update($request->all());
        return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully!');

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

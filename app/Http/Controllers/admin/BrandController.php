<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    
    /**
     * Trang danh sách thương hiệu.
     * Hiển thị danh sách thương hiệu với phân trang và tìm kiếm.
     */
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

    /* 
     * Hiển thị form để thêm thương hiệu mới.
     */
    public function create()
    {
        return view('admin.brands.add');
    }

    /**
     * Xử lý lưu thương hiệu mới.
     * Validate dữ liệu và lưu vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        try {
            // Validate input with custom messages
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name',
                'description' => 'nullable|string|max:1000'
            ], [
                'name.required' => 'Tên thương hiệu là bắt buộc.',
                'name.string' => 'Tên thương hiệu phải là chuỗi ký tự.',
                'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
                'name.unique' => 'Tên thương hiệu đã tồn tại.',
                'description.string' => 'Mô tả phải là chuỗi ký tự.',
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự.'
            ]);

            // Create brand
            $brand = Brand::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
                'status' => Brand::STATUS_ACTIVE // Default to active
            ]);

            return redirect()->route('brands.index')
                ->with('success', "Thương hiệu '{$brand->name}' đã được thêm thành công!");
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    /** Hiển thị form để chỉnh sửa thương hiệu.
     * Nhận đối tượng Brand từ route model binding.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    // Update brand
    public function update(Request $request, Brand $brand)
    {
        try {
            // Validate input with custom messages
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
                'description' => 'nullable|string|max:1000'
            ], [
                'name.required' => 'Tên thương hiệu là bắt buộc.',
                'name.string' => 'Tên thương hiệu phải là chuỗi ký tự.',
                'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
                'name.unique' => 'Tên thương hiệu đã tồn tại.',
                'description.string' => 'Mô tả phải là chuỗi ký tự.',
                'description.max' => 'Mô tả không được vượt quá 1000 ký tự.'
            ]);

            // Update brand
            $brand->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? ''
            ]);

            return redirect()->route('brands.index')
                ->with('success', "Thương hiệu '{$brand->name}' đã được cập nhật thành công!");
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    // Toggle brand status
    public function toggleStatus(Brand $brand)
    {
        // Use model's built-in methods
        $brand->status === Brand::STATUS_ACTIVE
            ? $brand->deactivate()
            : $brand->activate();

        $statusMessage = $brand->status === Brand::STATUS_ACTIVE
            ? 'activated'
            : 'deactivated';

        return redirect()->route('brands.index')
            ->with('success', "Brand '{$brand->name}' has been $statusMessage.");
    }

}
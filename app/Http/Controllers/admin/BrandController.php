<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    // Display list of brands with search and filter
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

    // Show create brand form
    public function create()
    {
        return view('admin.brands.add');
    }

    // Store a new brand
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name',
                'description' => 'nullable|string|max:1000'
            ]);

            // Create brand
            $brand = Brand::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
                'status' => Brand::STATUS_ACTIVE // Default to active
            ]);

            return redirect()->route('brands.index')
                ->with('success', "Brand '{$brand->name}' added successfully!");
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    // Edit brand form
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    // Update brand
    public function update(Request $request, Brand $brand)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
                'description' => 'nullable|string|max:1000'
            ]);

            // Update brand
            $brand->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? ''
            ]);

            return redirect()->route('brands.index')
                ->with('success', "Brand '{$brand->name}' updated successfully!");
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
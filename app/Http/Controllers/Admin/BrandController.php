<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index(Request $request): View
    {
        $query = Brand::query();

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $brands = $query->latest()->paginate(10)->withQueryString();

        $title = getPageTitle('Brands');

        return view('manage.admin.brands.index', compact('brands', 'title'));
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create(): View
    {
        $title = getPageTitle('Create Brand');
        return view('manage.admin.brands.create', compact('title'));
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Upload Brand Logo
        if ($request->hasFile('logo')) {
            $logoName = time() . '_logo_' . Str::random(6) . '.' . $request->file('logo')->extension();
            $request->file('logo')->move(public_path('uploads/brands/logos'), $logoName);
            $data['logo'] = 'uploads/brands/logos/' . $logoName;
        }

        $data['created_by'] = Auth::guard('admin')->id();

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified brand details.
     */
    public function show(Brand $brand): View
    {
        $brand->load('creator', 'updater');
        return view('manage.admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit(Brand $brand): View
    {
        $title = getPageTitle('Edit Brand');
        return view('manage.admin.brands.edit', compact('brand', 'title'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->validated();

        // Slug handling
        if (!empty($data['slug']) && $data['slug'] !== $brand->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } elseif (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle Logo Update
        if ($request->hasFile('logo')) {
            if ($brand->logo && File::exists(public_path($brand->logo))) {
                File::delete(public_path($brand->logo));
            }

            $logoName = time() . '_logo_' . Str::random(6) . '.' . $request->file('logo')->extension();
            $request->file('logo')->move(public_path('uploads/brands/logos'), $logoName);
            $data['logo'] = 'uploads/brands/logos/' . $logoName;
        }

        $data['updated_by'] = Auth::guard('admin')->id();

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified brand from storage (Soft Delete).
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand moved to trash successfully.');
    }

    /**
     * Restore a soft-deleted brand.
     */
    public function restore($id): RedirectResponse
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->restore();

        return redirect()->route('admin.brands.index', ['trashed' => 1])->with('success', 'Brand restored successfully.');
    }

    /**
     * Permanently delete a brand from storage.
     */
    public function forceDelete($id): RedirectResponse
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);

        // Delete associated logo file
        if ($brand->logo && File::exists(public_path($brand->logo))) {
            File::delete(public_path($brand->logo));
        }

        $brand->forceDelete();

        return redirect()->route('admin.brands.index', ['trashed' => 1])->with('success', 'Brand permanently deleted.');
    }

    /**
     * Toggle the active/inactive status of a brand.
     */
    public function toggleStatus(Brand $brand): RedirectResponse
    {
        $newStatus = $brand->status === 'active' ? 'inactive' : 'active';
        $brand->update([
            'status' => $newStatus,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Brand status changed to {$newStatus}.");
    }
}

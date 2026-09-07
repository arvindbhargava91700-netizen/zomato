<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFoodCategoryRequest;
use App\Http\Requests\Admin\UpdateFoodCategoryRequest;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of all food categories.
     */
    public function index(Request $request): View
    {
        $query = FoodCategory::query();

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();

        return view('manage.admin.food-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new food category.
     */
    public function create(): View
    {
        return view('manage.admin.food-categories.create');
    }

    /**
     * Store a newly created food category in storage.
     */
    public function store(StoreFoodCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Generate unique slug
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;
        while (FoodCategory::where('slug', $slug)->whereNull('deleted_at')->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $data['slug'] = $slug;

        // Upload Category Image
        if ($request->hasFile('image')) {
            $imageName = time() . '_cat_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/' . $imageName;
        }

        $data['created_by'] = Auth::guard('admin')->id();

        FoodCategory::create($data);

        return redirect()->route('admin.food-categories.index')->with('success', 'Food Category created successfully.');
    }

    /**
     * Display the specified food category details.
     */
    public function show(FoodCategory $foodCategory): View
    {
        $foodCategory->load('restaurant', 'creator', 'updater');
        return view('manage.admin.food-categories.show', compact('foodCategory'));
    }

    /**
     * Show the form for editing the specified food category.
     */
    public function edit(FoodCategory $foodCategory): View
    {
        return view('manage.admin.food-categories.edit', compact('foodCategory'));
    }

    /**
     * Update the specified food category in storage.
     */
    public function update(UpdateFoodCategoryRequest $request, FoodCategory $foodCategory): RedirectResponse
    {
        $data = $request->validated();

        // Slug handling
        if (!empty($data['slug']) && $data['slug'] !== $foodCategory->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } else {
            unset($data['slug']);
        }

        // Image upload
        if ($request->hasFile('image')) {
            if ($foodCategory->image && File::exists(public_path($foodCategory->image))) {
                File::delete(public_path($foodCategory->image));
            }
            $imageName = time() . '_cat_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/' . $imageName;
        }

        $data['updated_by'] = Auth::guard('admin')->id();

        $foodCategory->update($data);

        return redirect()->route('admin.food-categories.index')->with('success', 'Food Category updated successfully.');
    }

    /**
     * Soft delete the specified food category.
     */
    public function destroy(FoodCategory $foodCategory): RedirectResponse
    {
        $foodCategory->delete();
        return redirect()->route('admin.food-categories.index')->with('success', 'Food Category soft-deleted successfully.');
    }

    /**
     * Restore soft deleted category.
     */
    public function restore($id): RedirectResponse
    {
        $category = FoodCategory::onlyTrashed()->findOrFail($id);
        $category->restore();

        return back()->with('success', 'Food Category restored successfully.');
    }

    /**
     * Permanently delete category.
     */
    public function forceDelete($id): RedirectResponse
    {
        $category = FoodCategory::onlyTrashed()->findOrFail($id);

        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->forceDelete();

        return back()->with('success', 'Food Category permanently deleted.');
    }

    /**
     * Toggle status active/inactive.
     */
    public function toggleStatus(Request $request, FoodCategory $foodCategory)
    {
        $newStatus = $foodCategory->status === 'active' ? 'inactive' : 'active';
        $foodCategory->update(['status' => $newStatus, 'updated_by' => Auth::guard('admin')->id()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => "Food Category status updated to " . ucfirst($newStatus) . "."
            ]);
        }

        return back()->with('success', "Food Category status updated to " . ucfirst($newStatus) . ".");
    }
}

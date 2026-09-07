<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FoodCategoryController extends Controller
{
    /**
     * Get owner's assigned restaurant model or abort.
     */
    private function getAssignedRestaurant(): Restaurant
    {
        $owner = auth()->user();
        $restaurant = Restaurant::where('user_id', $owner?->id)->first();

        if (!$restaurant) {
            abort(403, 'No restaurant assigned to your account. Please contact administrator.');
        }

        return $restaurant;
    }

    /**
     * Display listing of categories belonging to owner's restaurant.
     */
    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = FoodCategory::where('restaurant_id', $restaurant->id);

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();

        return view('manage.restaurant.food-categories.index', compact('categories', 'restaurant'));
    }

    /**
     * Show form for creating category for owner's restaurant.
     */
    public function create(): View
    {
        $restaurant = $this->getAssignedRestaurant();
        return view('manage.restaurant.food-categories.create', compact('restaurant'));
    }

    /**
     * Store a new category for owner's restaurant.
     */
    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('food_categories', 'slug')->where('restaurant_id', $restaurant->id)->whereNull('deleted_at'),
            ],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data = $request->only('name', 'slug', 'description', 'sort_order', 'status');
        $data['restaurant_id'] = $restaurant->id;

        // Slug generation
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;
        while (FoodCategory::where('restaurant_id', $restaurant->id)->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $data['slug'] = $slug;

        // Image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '_cat_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/' . $imageName;
        }

        $data['created_by'] = auth()->id();

        FoodCategory::create($data);

        return redirect()->route('restaurant.food-categories.index')->with('success', 'Food Category created successfully.');
    }

    /**
     * Display details of owner's category.
     */
    public function show(FoodCategory $foodCategory): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodCategory->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        return view('manage.restaurant.food-categories.show', compact('foodCategory', 'restaurant'));
    }

    /**
     * Show edit form for owner's category.
     */
    public function edit(FoodCategory $foodCategory): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodCategory->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        return view('manage.restaurant.food-categories.edit', compact('foodCategory', 'restaurant'));
    }

    /**
     * Update owner's category.
     */
    public function update(Request $request, FoodCategory $foodCategory): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodCategory->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('food_categories', 'slug')
                    ->where('restaurant_id', $restaurant->id)
                    ->whereNull('deleted_at')
                    ->ignore($foodCategory->id),
            ],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data = $request->only('name', 'slug', 'description', 'sort_order', 'status');

        if (!empty($data['slug']) && $data['slug'] !== $foodCategory->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } else {
            unset($data['slug']);
        }

        if ($request->hasFile('image')) {
            if ($foodCategory->image && File::exists(public_path($foodCategory->image))) {
                File::delete(public_path($foodCategory->image));
            }
            $imageName = time() . '_cat_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/' . $imageName;
        }

        $data['updated_by'] = auth()->id();

        $foodCategory->update($data);

        return redirect()->route('restaurant.food-categories.index')->with('success', 'Food Category updated successfully.');
    }

    /**
     * Soft delete owner's category.
     */
    public function destroy(FoodCategory $foodCategory): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodCategory->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        $foodCategory->delete();
        return redirect()->route('restaurant.food-categories.index')->with('success', 'Food Category soft-deleted.');
    }

    /**
     * Restore owner's soft deleted category.
     */
    public function restore($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $category = FoodCategory::onlyTrashed()->where('restaurant_id', $restaurant->id)->findOrFail($id);
        $category->restore();

        return back()->with('success', 'Food Category restored.');
    }

    /**
     * Permanently delete owner's category.
     */
    public function forceDelete($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $category = FoodCategory::onlyTrashed()->where('restaurant_id', $restaurant->id)->findOrFail($id);

        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->forceDelete();

        return back()->with('success', 'Food Category permanently deleted.');
    }

    /**
     * Toggle status active/inactive.
     */
    public function toggleStatus(FoodCategory $foodCategory): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodCategory->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this category.');
        }

        $newStatus = $foodCategory->status === 'active' ? 'inactive' : 'active';
        $foodCategory->update(['status' => $newStatus, 'updated_by' => auth()->id()]);

        return back()->with('success', "Food Category status updated to {$newStatus}.");
    }
}


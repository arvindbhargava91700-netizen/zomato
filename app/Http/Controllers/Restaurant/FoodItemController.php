<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Cuisine;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FoodItemController extends Controller
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
     * Display listing of food items belonging to owner's restaurant.
     */
    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = Food::with('category', 'cuisines')->where('restaurant_id', $restaurant->id);

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('food_category_id', $request->category_id);
        }

        if ($request->filled('food_type')) {
            $query->where('food_type', $request->food_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $foods = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();
        $categories = FoodCategory::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.restaurant.foods.index', compact('foods', 'restaurant', 'categories'));
    }

    /**
     * Show form for creating food item for owner's restaurant.
     */
    public function create(): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $categories = FoodCategory::where('status', 'active')->orderBy('name', 'asc')->get();
        $cuisines = $restaurant->cuisines()->where('cuisines.status', 'active')->orderBy('name', 'asc')->get();
        $existingFoods = Food::where('restaurant_id', $restaurant->id)
            ->with(['category', 'variants'])
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->get();

        return view('manage.restaurant.foods.create', compact('restaurant', 'categories', 'cuisines', 'existingFoods'));
    }

    /**
     * Store a new food item for owner's restaurant.
     */
    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $request->validate([
            'food_category_id' => ['required', 'exists:food_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('foods', 'slug')->where('restaurant_id', $restaurant->id)->whereNull('deleted_at'),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'food_type' => ['required', 'in:veg,non_veg,egg'],
            'is_veg' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_recommended' => ['nullable', 'boolean'],
            'is_spicy' => ['nullable', 'boolean'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cuisine_ids' => ['nullable', 'array'],
            'cuisine_ids.*' => ['exists:cuisines,id'],
        ]);

        $data = $request->only(
            'food_category_id', 'name', 'slug', 'short_description', 'description', 'sku',
            'food_type', 'preparation_time', 'base_price', 'discount_price', 'tax_percentage',
            'status', 'sort_order'
        );

        $data['restaurant_id'] = $restaurant->id;

        // Auto slug per restaurant
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Food::where('restaurant_id', $restaurant->id)->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $data['slug'] = $slug;

        $data['is_veg'] = ($data['food_type'] === 'veg');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_recommended'] = $request->boolean('is_recommended');
        $data['is_spicy'] = $request->boolean('is_spicy');

        if ($request->hasFile('image')) {
            $imageName = time() . '_food_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/foods'), $imageName);
            $data['image'] = 'uploads/foods/' . $imageName;
        }

        $data['created_by'] = auth()->id();

        $food = Food::create($data);

        if (!empty($request->cuisine_ids)) {
            $food->cuisines()->sync($request->cuisine_ids);
        }

        return redirect()->route('restaurant.foods.index')->with('success', 'Food Item created successfully.');
    }

    /**
     * Display owner's food item details.
     */
    public function show(Food $food): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food item.');
        }

        $food->load('category', 'cuisines');

        return view('manage.restaurant.foods.show', compact('food', 'restaurant'));
    }

    /**
     * Show edit form for owner's food item.
     */
    public function edit(Food $food): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food item.');
        }

        $categories = FoodCategory::where('status', 'active')->orderBy('name', 'asc')->get();
        $cuisines = $restaurant->cuisines()->where('cuisines.status', 'active')->orderBy('name', 'asc')->get();
        $assignedCuisineIds = $food->cuisines()->pluck('cuisines.id')->toArray();

        return view('manage.restaurant.foods.edit', compact('food', 'restaurant', 'categories', 'cuisines', 'assignedCuisineIds'));
    }

    /**
     * Update owner's food item.
     */
    public function update(Request $request, Food $food): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food item.');
        }

        $request->validate([
            'food_category_id' => ['required', 'exists:food_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('foods', 'slug')
                    ->where('restaurant_id', $restaurant->id)
                    ->whereNull('deleted_at')
                    ->ignore($food->id),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'food_type' => ['required', 'in:veg,non_veg,egg'],
            'is_veg' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_recommended' => ['nullable', 'boolean'],
            'is_spicy' => ['nullable', 'boolean'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cuisine_ids' => ['nullable', 'array'],
            'cuisine_ids.*' => ['exists:cuisines,id'],
        ]);

        $data = $request->only(
            'food_category_id', 'name', 'slug', 'short_description', 'description', 'sku',
            'food_type', 'preparation_time', 'base_price', 'discount_price', 'tax_percentage',
            'status', 'sort_order'
        );

        if (!empty($data['slug']) && $data['slug'] !== $food->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } else {
            unset($data['slug']);
        }

        $data['is_veg'] = ($data['food_type'] === 'veg');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_recommended'] = $request->boolean('is_recommended');
        $data['is_spicy'] = $request->boolean('is_spicy');

        if ($request->hasFile('image')) {
            if ($food->image && File::exists(public_path($food->image))) {
                File::delete(public_path($food->image));
            }
            $imageName = time() . '_food_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/foods'), $imageName);
            $data['image'] = 'uploads/foods/' . $imageName;
        }

        $data['updated_by'] = auth()->id();

        $food->update($data);

        $food->cuisines()->sync($request->cuisine_ids ?? []);

        return redirect()->route('restaurant.foods.index')->with('success', 'Food Item updated successfully.');
    }

    /**
     * Soft delete owner's food item.
     */
    public function destroy(Food $food): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food item.');
        }

        $food->delete();
        return redirect()->route('restaurant.foods.index')->with('success', 'Food Item soft-deleted.');
    }

    /**
     * Restore owner's soft deleted food item.
     */
    public function restore($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $food = Food::onlyTrashed()->where('restaurant_id', $restaurant->id)->findOrFail($id);
        $food->restore();

        return back()->with('success', 'Food Item restored.');
    }

    /**
     * Permanently delete owner's food item.
     */
    public function forceDelete($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $food = Food::onlyTrashed()->where('restaurant_id', $restaurant->id)->findOrFail($id);

        if ($food->image && File::exists(public_path($food->image))) {
            File::delete(public_path($food->image));
        }

        $food->forceDelete();

        return back()->with('success', 'Food Item permanently deleted.');
    }

    /**
     * Toggle status active/inactive.
     */
    public function toggleStatus(Food $food): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food item.');
        }

        $newStatus = $food->status === 'active' ? 'inactive' : 'active';
        $food->update(['status' => $newStatus, 'updated_by' => auth()->id()]);

        return back()->with('success', "Food Item status updated to {$newStatus}.");
    }
}


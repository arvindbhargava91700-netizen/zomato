<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFoodItemRequest;
use App\Http\Requests\Admin\UpdateFoodItemRequest;
use App\Models\Cuisine;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FoodItemController extends Controller
{
    /**
     * Display a listing of all food items.
     */
    public function index(Request $request): View
    {
        $query = Food::with('restaurant', 'category', 'cuisines');

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

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        if ($request->filled('category_id')) {
            $query->where('food_category_id', $request->category_id);
        }

        if ($request->filled('cuisine_id')) {
            $query->whereHas('cuisines', function ($cq) use ($request) {
                $cq->where('cuisines.id', $request->cuisine_id);
            });
        }

        if ($request->filled('food_type')) {
            $query->where('food_type', $request->food_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->filled('is_recommended')) {
            $query->where('is_recommended', $request->boolean('is_recommended'));
        }

        $foods = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();

        $restaurants = Restaurant::orderBy('restaurant_name', 'asc')->get();
        $categories = FoodCategory::orderBy('name', 'asc')->get();
        $cuisines = Cuisine::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.admin.foods.index', compact('foods', 'restaurants', 'categories', 'cuisines'));
    }

    /**
     * Show form for creating a new food item.
     */
    public function create(): View
    {
        $restaurants = Restaurant::where('status', 'active')->orderBy('restaurant_name', 'asc')->get();
        $categories = FoodCategory::where('status', 'active')->orderBy('name', 'asc')->get();
        $cuisines = Cuisine::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.admin.foods.create', compact('restaurants', 'categories', 'cuisines'));
    }

    /**
     * Store a newly created food item.
     */
    public function store(StoreFoodItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Auto slug generation per restaurant
        $slug = $data['slug'] ?? Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Food::where('restaurant_id', $data['restaurant_id'])->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $data['slug'] = $slug;

        // Auto set is_veg boolean based on food_type
        $data['is_veg'] = ($data['food_type'] === 'veg');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_recommended'] = $request->boolean('is_recommended');
        $data['is_spicy'] = $request->boolean('is_spicy');

        // Image Upload
        if ($request->hasFile('image')) {
            $imageName = time() . '_food_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/foods'), $imageName);
            $data['image'] = 'uploads/foods/' . $imageName;
        }

        $data['created_by'] = Auth::guard('admin')->id();

        $food = Food::create($data);

        // Sync Cuisines
        if (!empty($request->cuisine_ids)) {
            $food->cuisines()->sync($request->cuisine_ids);
        }

        return redirect()->route('admin.foods.index')->with('success', 'Food Item created successfully.');
    }

    /**
     * Display food item details.
     */
    public function show(Food $food): View
    {
        $food->load('restaurant', 'category', 'cuisines', 'creator', 'updater');
        return view('manage.admin.foods.show', compact('food'));
    }

    /**
     * Show form for editing food item.
     */
    public function edit(Food $food): View
    {
        $restaurants = Restaurant::where('status', 'active')->orderBy('restaurant_name', 'asc')->get();
        $categories = FoodCategory::where('restaurant_id', $food->restaurant_id)->where('status', 'active')->orderBy('name', 'asc')->get();
        $cuisines = Cuisine::where('status', 'active')->orderBy('name', 'asc')->get();
        $assignedCuisineIds = $food->cuisines()->pluck('cuisines.id')->toArray();

        return view('manage.admin.foods.edit', compact('food', 'restaurants', 'categories', 'cuisines', 'assignedCuisineIds'));
    }

    /**
     * Update food item.
     */
    public function update(UpdateFoodItemRequest $request, Food $food): RedirectResponse
    {
        $data = $request->validated();

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

        $data['updated_by'] = Auth::guard('admin')->id();

        $food->update($data);

        $food->cuisines()->sync($request->cuisine_ids ?? []);

        return redirect()->route('admin.foods.index')->with('success', 'Food Item updated successfully.');
    }

    /**
     * Soft delete food item.
     */
    public function destroy(Food $food): RedirectResponse
    {
        $food->delete();
        return redirect()->route('admin.foods.index')->with('success', 'Food Item soft-deleted successfully.');
    }

    /**
     * Restore soft deleted food item.
     */
    public function restore($id): RedirectResponse
    {
        $food = Food::onlyTrashed()->findOrFail($id);
        $food->restore();

        return back()->with('success', 'Food Item restored successfully.');
    }

    /**
     * Permanently delete food item.
     */
    public function forceDelete($id): RedirectResponse
    {
        $food = Food::onlyTrashed()->findOrFail($id);

        if ($food->image && File::exists(public_path($food->image))) {
            File::delete(public_path($food->image));
        }

        $food->forceDelete();

        return back()->with('success', 'Food Item permanently deleted.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Food $food): RedirectResponse
    {
        $newStatus = $food->status === 'active' ? 'inactive' : 'active';
        $food->update(['status' => $newStatus, 'updated_by' => Auth::guard('admin')->id()]);

        return back()->with('success', "Food Item status updated to {$newStatus}.");
    }

    /**
     * Get existing food items for a restaurant (AJAX).
     */
    public function getRestaurantFoods(Restaurant $restaurant)
    {
        $foods = Food::where('restaurant_id', $restaurant->id)
            ->with(['category', 'variants'])
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->get()
            ->map(function ($food) {
                return [
                    'id' => $food->id,
                    'name' => $food->name,
                    'category_name' => $food->category ? $food->category->name : 'N/A',
                    'base_price' => number_format($food->base_price, 2),
                    'discount_price' => $food->discount_price ? number_format($food->discount_price, 2) : null,
                    'food_type' => $food->food_type,
                    'status' => $food->status,
                    'image' => $food->image ? asset($food->image) : asset('front/assets/images/menu/13.jpg'),
                    'variants_count' => $food->variants->count(),
                    'variants' => $food->variants->map(function ($v) {
                        return [
                            'id' => $v->id,
                            'name' => $v->variant_name,
                            'price' => number_format($v->price, 2),
                            'sale_price' => $v->sale_price ? number_format($v->sale_price, 2) : null,
                            'display_price' => number_format($v->sale_price ?: $v->price, 2),
                        ];
                    }),
                    'edit_url' => route('admin.foods.edit', $food->id),
                ];
            });

        return response()->json([
            'status' => true,
            'restaurant_name' => $restaurant->restaurant_name,
            'foods' => $foods,
        ]);
    }
}

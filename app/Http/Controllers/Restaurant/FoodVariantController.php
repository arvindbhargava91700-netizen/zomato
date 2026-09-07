<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodVariant;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FoodVariantController extends Controller
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
     * Display listing of food variants for owner's food items.
     */
    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = FoodVariant::with('food')
            ->whereHas('food', function ($fq) use ($restaurant) {
                $fq->where('restaurant_id', $restaurant->id);
            });

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('variant_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('food', function ($fq) use ($search) {
                      $fq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('food_id')) {
            $query->where('food_id', $request->food_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $variants = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();
        $foods = Food::where('restaurant_id', $restaurant->id)->where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.restaurant.food-variants.index', compact('variants', 'foods', 'restaurant'));
    }

    /**
     * Show form for creating variant for owner's food item.
     */
    public function create(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();
        $selectedFoodId = $request->query('food_id');

        $foods = Food::where('restaurant_id', $restaurant->id)->where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.restaurant.food-variants.create', compact('foods', 'restaurant', 'selectedFoodId'));
    }

    /**
     * Store new variant for owner's food item.
     */
    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'variant_name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'string', 'max:20'],
            'serving_size' => ['nullable', 'string', 'max:100'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Security Check: Verify Food Item belongs to owner's restaurant
        $food = Food::where('restaurant_id', $restaurant->id)->findOrFail($request->food_id);

        $data = $request->only(
            'food_id', 'variant_name', 'sku', 'price', 'sale_price', 'cost_price',
            'weight', 'weight_unit', 'serving_size', 'preparation_time', 'status', 'sort_order'
        );

        $data['restaurant_id'] = $restaurant->id;
        $data['created_by'] = auth()->id();

        FoodVariant::create($data);

        return redirect()->route('restaurant.food-variants.index')->with('success', 'Food Variant created successfully.');
    }

    /**
     * Display owner's variant details.
     */
    public function show(FoodVariant $foodVariant): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodVariant->food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food variant.');
        }

        $foodVariant->load('food');

        return view('manage.restaurant.food-variants.show', compact('foodVariant', 'restaurant'));
    }

    /**
     * Show edit form for owner's variant.
     */
    public function edit(FoodVariant $foodVariant): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodVariant->food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food variant.');
        }

        $foods = Food::where('restaurant_id', $restaurant->id)->where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.restaurant.food-variants.edit', compact('foodVariant', 'foods', 'restaurant'));
    }

    /**
     * Update owner's variant.
     */
    public function update(Request $request, FoodVariant $foodVariant): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodVariant->food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food variant.');
        }

        $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'variant_name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'string', 'max:20'],
            'serving_size' => ['nullable', 'string', 'max:100'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        Food::where('restaurant_id', $restaurant->id)->findOrFail($request->food_id);

        $data = $request->only(
            'food_id', 'variant_name', 'sku', 'price', 'sale_price', 'cost_price',
            'weight', 'weight_unit', 'serving_size', 'preparation_time', 'status', 'sort_order'
        );

        $data['restaurant_id'] = $restaurant->id;
        $data['updated_by'] = auth()->id();

        $foodVariant->update($data);

        return redirect()->route('restaurant.food-variants.index')->with('success', 'Food Variant updated successfully.');
    }

    /**
     * Soft delete owner's variant.
     */
    public function destroy(FoodVariant $foodVariant): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodVariant->food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food variant.');
        }

        $foodVariant->delete();
        return redirect()->route('restaurant.food-variants.index')->with('success', 'Food Variant soft-deleted.');
    }

    /**
     * Restore owner's soft deleted variant.
     */
    public function restore($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $variant = FoodVariant::onlyTrashed()->whereHas('food', function ($fq) use ($restaurant) {
            $fq->where('restaurant_id', $restaurant->id);
        })->findOrFail($id);

        $variant->restore();

        return back()->with('success', 'Food Variant restored.');
    }

    /**
     * Permanently delete owner's variant.
     */
    public function forceDelete($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $variant = FoodVariant::onlyTrashed()->whereHas('food', function ($fq) use ($restaurant) {
            $fq->where('restaurant_id', $restaurant->id);
        })->findOrFail($id);

        $variant->forceDelete();

        return back()->with('success', 'Food Variant permanently deleted.');
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(FoodVariant $foodVariant): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($foodVariant->food->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this food variant.');
        }

        $newStatus = $foodVariant->status === 'active' ? 'inactive' : 'active';
        $foodVariant->update(['status' => $newStatus, 'updated_by' => auth()->id()]);

        return back()->with('success', "Food Variant status updated to {$newStatus}.");
    }
}


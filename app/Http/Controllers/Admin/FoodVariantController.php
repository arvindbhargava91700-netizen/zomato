<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFoodVariantRequest;
use App\Http\Requests\Admin\UpdateFoodVariantRequest;
use App\Models\Food;
use App\Models\FoodVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FoodVariantController extends Controller
{
    /**
     * Display a listing of all food variants.
     */
    public function index(Request $request): View
    {
        $query = FoodVariant::with('food.restaurant');

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
        $foods = Food::orderBy('name', 'asc')->get();

        return view('manage.admin.food-variants.index', compact('variants', 'foods'));
    }

    /**
     * Show form for creating a new food variant.
     */
    public function create(Request $request): View
    {
        $selectedFoodId = $request->query('food_id');
        $foods = Food::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('manage.admin.food-variants.create', compact('foods', 'selectedFoodId'));
    }

    /**
     * Store a newly created variant.
     */
    public function store(StoreFoodVariantRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = Auth::guard('admin')->id();

        FoodVariant::create($data);

        return redirect()->route('admin.food-variants.index')->with('success', 'Food Variant created successfully.');
    }

    /**
     * Display variant details.
     */
    public function show(FoodVariant $foodVariant): View
    {
        $foodVariant->load('food.restaurant', 'creator', 'updater');
        return view('manage.admin.food-variants.show', compact('foodVariant'));
    }

    /**
     * Show form for editing variant.
     */
    public function edit(FoodVariant $foodVariant): View
    {
        $foods = Food::where('status', 'active')->orderBy('name', 'asc')->get();
        return view('manage.admin.food-variants.edit', compact('foodVariant', 'foods'));
    }

    /**
     * Update variant.
     */
    public function update(UpdateFoodVariantRequest $request, FoodVariant $foodVariant): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::guard('admin')->id();

        $foodVariant->update($data);

        return redirect()->route('admin.food-variants.index')->with('success', 'Food Variant updated successfully.');
    }

    /**
     * Soft delete variant.
     */
    public function destroy(FoodVariant $foodVariant): RedirectResponse
    {
        $foodVariant->delete();
        return redirect()->route('admin.food-variants.index')->with('success', 'Food Variant soft-deleted.');
    }

    /**
     * Restore soft deleted variant.
     */
    public function restore($id): RedirectResponse
    {
        $variant = FoodVariant::onlyTrashed()->findOrFail($id);
        $variant->restore();

        return back()->with('success', 'Food Variant restored.');
    }

    /**
     * Permanently delete variant.
     */
    public function forceDelete($id): RedirectResponse
    {
        $variant = FoodVariant::onlyTrashed()->findOrFail($id);
        $variant->forceDelete();

        return back()->with('success', 'Food Variant permanently deleted.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(FoodVariant $foodVariant): RedirectResponse
    {
        $newStatus = $foodVariant->status === 'active' ? 'inactive' : 'active';
        $foodVariant->update(['status' => $newStatus, 'updated_by' => Auth::guard('admin')->id()]);

        return back()->with('success', "Food Variant status updated to {$newStatus}.");
    }
}

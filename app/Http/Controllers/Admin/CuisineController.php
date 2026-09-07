<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCuisineRequest;
use App\Http\Requests\Admin\UpdateCuisineRequest;
use App\Models\Cuisine;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CuisineController extends Controller
{
    /**
     * Display a listing of cuisines.
     */
    public function index(Request $request): View
    {
        $query = Cuisine::withCount('restaurants');

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

        $cuisines = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();

        return view('manage.admin.cuisines.index', compact('cuisines'));
    }

    /**
     * Show form for creating a new cuisine.
     */
    public function create(): View
    {
        $restaurants = Restaurant::where('status', 'active')->orderBy('restaurant_name', 'asc')->get();
        return view('manage.admin.cuisines.create', compact('restaurants'));
    }

    /**
     * Store a newly created cuisine.
     */
    public function store(StoreCuisineRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Upload Icon
        if ($request->hasFile('icon')) {
            $iconName = time() . '_icon_' . Str::random(5) . '.' . $request->file('icon')->extension();
            $request->file('icon')->move(public_path('uploads/cuisines/icons'), $iconName);
            $data['icon'] = 'uploads/cuisines/icons/' . $iconName;
        }

        // Upload Image
        if ($request->hasFile('image')) {
            $imageName = time() . '_img_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/cuisines/images'), $imageName);
            $data['image'] = 'uploads/cuisines/images/' . $imageName;
        }

        $data['created_by'] = Auth::guard('admin')->id();

        $cuisine = Cuisine::create($data);

        // Sync assigned restaurants if selected
        if (!empty($request->restaurant_ids)) {
            $cuisine->restaurants()->sync($request->restaurant_ids);
        }

        return redirect()->route('admin.cuisines.index')->with('success', 'Cuisine created successfully.');
    }

    /**
     * Display specified cuisine details.
     */
    public function show(Cuisine $cuisine): View
    {
        $cuisine->load('restaurants', 'creator', 'updater');
        return view('manage.admin.cuisines.show', compact('cuisine'));
    }

    /**
     * Show form for editing specified cuisine.
     */
    public function edit(Cuisine $cuisine): View
    {
        $restaurants = Restaurant::where('status', 'active')->orderBy('restaurant_name', 'asc')->get();
        $assignedRestaurantIds = $cuisine->restaurants()->pluck('restaurants.id')->toArray();

        return view('manage.admin.cuisines.edit', compact('cuisine', 'restaurants', 'assignedRestaurantIds'));
    }

    /**
     * Update specified cuisine.
     */
    public function update(UpdateCuisineRequest $request, Cuisine $cuisine): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['slug']) && $data['slug'] !== $cuisine->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } else {
            unset($data['slug']);
        }

        // Icon upload
        if ($request->hasFile('icon')) {
            if ($cuisine->icon && File::exists(public_path($cuisine->icon))) {
                File::delete(public_path($cuisine->icon));
            }
            $iconName = time() . '_icon_' . Str::random(5) . '.' . $request->file('icon')->extension();
            $request->file('icon')->move(public_path('uploads/cuisines/icons'), $iconName);
            $data['icon'] = 'uploads/cuisines/icons/' . $iconName;
        }

        // Image upload
        if ($request->hasFile('image')) {
            if ($cuisine->image && File::exists(public_path($cuisine->image))) {
                File::delete(public_path($cuisine->image));
            }
            $imageName = time() . '_img_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/cuisines/images'), $imageName);
            $data['image'] = 'uploads/cuisines/images/' . $imageName;
        }

        $data['updated_by'] = Auth::guard('admin')->id();

        $cuisine->update($data);

        // Sync assigned restaurants
        $cuisine->restaurants()->sync($request->restaurant_ids ?? []);

        return redirect()->route('admin.cuisines.index')->with('success', 'Cuisine updated successfully.');
    }

    /**
     * Soft delete specified cuisine.
     */
    public function destroy(Cuisine $cuisine): RedirectResponse
    {
        $cuisine->delete();
        return redirect()->route('admin.cuisines.index')->with('success', 'Cuisine soft-deleted successfully.');
    }

    /**
     * Restore soft deleted cuisine.
     */
    public function restore($id): RedirectResponse
    {
        $cuisine = Cuisine::onlyTrashed()->findOrFail($id);
        $cuisine->restore();

        return back()->with('success', 'Cuisine restored successfully.');
    }

    /**
     * Permanently delete cuisine.
     */
    public function forceDelete($id): RedirectResponse
    {
        $cuisine = Cuisine::onlyTrashed()->findOrFail($id);

        if ($cuisine->icon && File::exists(public_path($cuisine->icon))) {
            File::delete(public_path($cuisine->icon));
        }

        if ($cuisine->image && File::exists(public_path($cuisine->image))) {
            File::delete(public_path($cuisine->image));
        }

        $cuisine->forceDelete();

        return back()->with('success', 'Cuisine permanently deleted.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Cuisine $cuisine): RedirectResponse
    {
        $newStatus = $cuisine->status === 'active' ? 'inactive' : 'active';
        $cuisine->update(['status' => $newStatus, 'updated_by' => Auth::guard('admin')->id()]);

        return back()->with('success', "Cuisine status updated to {$newStatus}.");
    }
}

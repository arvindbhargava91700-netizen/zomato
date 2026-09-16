<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantFeatureRequest;
use App\Http\Requests\Admin\UpdateRestaurantFeatureRequest;
use App\Models\RestaurantFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RestaurantFeatureController extends Controller
{
    /**
     * Display a listing of restaurant features.
     */
    public function index(Request $request): View
    {
        $query = RestaurantFeature::query();

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

        $features = $query->orderByRaw('LENGTH(sort_order), sort_order, name')->paginate(10)->withQueryString();

        $title = getPageTitle('Restaurant Features');
        return view('manage.admin.restaurant-features.index', compact('features', 'title'));
    }

    /**
     * Show the form for creating a new restaurant feature.
     */
    public function create(): View
    {
        $title = getPageTitle('Create Restaurant Feature');
        return view('manage.admin.restaurant-features.create', compact('title'));
    }

    /**
     * Store a newly created restaurant feature in storage.
     */
    public function store(StoreRestaurantFeatureRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['created_by'] = Auth::guard('admin')->id();

        RestaurantFeature::create($data);

        return redirect()->route('admin.restaurant-features.index')->with('success', 'Restaurant feature created successfully.');
    }

    /**
     * Display the specified restaurant feature.
     */
    public function show(RestaurantFeature $restaurantFeature): View
    {
        $restaurantFeature->load('creator', 'updater');
        return view('manage.admin.restaurant-features.show', compact('restaurantFeature'));
    }

    /**
     * Show the form for editing the specified restaurant feature.
     */
    public function edit(RestaurantFeature $restaurantFeature): View
    {
        $title = getPageTitle('Edit Restaurant Feature');
        return view('manage.admin.restaurant-features.edit', compact('restaurantFeature', 'title'));
    }

    /**
     * Update the specified restaurant feature in storage.
     */
    public function update(UpdateRestaurantFeatureRequest $request, RestaurantFeature $restaurantFeature): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['slug']) && $data['slug'] !== $restaurantFeature->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } elseif (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['updated_by'] = Auth::guard('admin')->id();

        $restaurantFeature->update($data);

        return redirect()->route('admin.restaurant-features.index')->with('success', 'Restaurant feature updated successfully.');
    }

    /**
     * Remove the specified restaurant feature from storage (Soft Delete).
     */
    public function destroy(RestaurantFeature $restaurantFeature): RedirectResponse
    {
        $restaurantFeature->delete();

        return redirect()->route('admin.restaurant-features.index')->with('success', 'Restaurant feature moved to trash successfully.');
    }

    /**
     * Restore a soft-deleted restaurant feature.
     */
    public function restore($id): RedirectResponse
    {
        $restaurantFeature = RestaurantFeature::onlyTrashed()->findOrFail($id);
        $restaurantFeature->restore();

        return redirect()->route('admin.restaurant-features.index', ['trashed' => 1])->with('success', 'Restaurant feature restored successfully.');
    }

    /**
     * Permanently delete a restaurant feature from storage.
     */
    public function forceDelete($id): RedirectResponse
    {
        $restaurantFeature = RestaurantFeature::onlyTrashed()->findOrFail($id);
        $restaurantFeature->forceDelete();

        return redirect()->route('admin.restaurant-features.index', ['trashed' => 1])->with('success', 'Restaurant feature permanently deleted.');
    }

    /**
     * Toggle the active/inactive status of a restaurant feature.
     */
    public function toggleStatus(RestaurantFeature $restaurantFeature): RedirectResponse
    {
        $newStatus = $restaurantFeature->status === 'active' ? 'inactive' : 'active';
        $restaurantFeature->update([
            'status' => $newStatus,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Feature status changed to {$newStatus}.");
    }
}

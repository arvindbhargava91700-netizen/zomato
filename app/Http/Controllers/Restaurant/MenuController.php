<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
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
     * Display listing of menus belonging to owner's restaurant.
     */
    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = RestaurantMenu::where('restaurant_id', $restaurant->id);

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

        $menus = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();

        return view('manage.restaurant.menus.index', compact('menus', 'restaurant'));
    }

    /**
     * Show form for creating a menu for owner's restaurant.
     */
    public function create(): View
    {
        $restaurant = $this->getAssignedRestaurant();

        return view('manage.restaurant.menus.create', compact('restaurant'));
    }

    /**
     * Store new menu images for owner's restaurant.
     */
    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
        ]);

        $count = 0;
        foreach ($request->file('images') as $file) {
            // Auto derive name + slug from uploaded file name
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name = $baseName ?: 'Menu ' . now()->format('d-m-Y');
            $slug = Str::slug($name);
            $originalSlug = $slug;
            $i = 1;
            while (RestaurantMenu::where('restaurant_id', $restaurant->id)->where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$i}";
                $i++;
            }

            $imageName = time() . '_menu_' . Str::random(5) . '.' . $file->extension();
            $file->move(public_path('uploads/menus'), $imageName);

            RestaurantMenu::create([
                'restaurant_id' => $restaurant->id,
                'name' => $name,
                'slug' => $slug,
                'image' => 'uploads/menus/' . $imageName,
                'status' => 'active',
                'sort_order' => 0,
                'created_by' => auth()->id(),
            ]);

            $count++;
        }

        $message = $count > 1
            ? "{$count} menu images uploaded successfully."
            : 'Menu uploaded successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('restaurant.menus.index'),
            ]);
        }

        return redirect()->route('restaurant.menus.index')->with('success', $message);
    }

    /**
     * Display owner's menu details.
     */
    public function show(RestaurantMenu $menu): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($menu->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this menu.');
        }

        return view('manage.restaurant.menus.show', compact('menu', 'restaurant'));
    }

    /**
     * Show edit form for owner's menu.
     */
    public function edit(RestaurantMenu $menu): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($menu->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this menu.');
        }

        return view('manage.restaurant.menus.edit', compact('menu', 'restaurant'));
    }

    /**
     * Update owner's menu image.
     */
    public function update(Request $request, RestaurantMenu $menu): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($menu->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this menu.');
        }

        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image && File::exists(public_path($menu->image))) {
                File::delete(public_path($menu->image));
            }
            $imageName = time() . '_menu_' . Str::random(5) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/menus'), $imageName);

            $menu->update([
                'image' => 'uploads/menus/' . $imageName,
                'updated_by' => auth()->id(),
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Menu updated successfully.',
                'redirect' => route('restaurant.menus.index'),
            ]);
        }

        return redirect()->route('restaurant.menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Soft delete owner's menu.
     */
    public function destroy(RestaurantMenu $menu): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($menu->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this menu.');
        }

        $menu->delete();
        return redirect()->route('restaurant.menus.index')->with('success', 'Menu soft-deleted.');
    }

    /**
     * Restore owner's soft deleted menu.
     */
    public function restore($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $menu = RestaurantMenu::onlyTrashed()->where('restaurant_id', $restaurant->id)->findOrFail($id);
        $menu->restore();

        return back()->with('success', 'Menu restored.');
    }

    /**
     * Permanently delete owner's menu.
     */
    public function forceDelete($id): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $menu = RestaurantMenu::onlyTrashed()->where('restaurant_id', $restaurant->id)->findOrFail($id);

        if ($menu->image && File::exists(public_path($menu->image))) {
            File::delete(public_path($menu->image));
        }

        $menu->forceDelete();

        return back()->with('success', 'Menu permanently deleted.');
    }

    /**
     * Toggle status active/inactive.
     */
    public function toggleStatus(RestaurantMenu $menu): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($menu->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this menu.');
        }

        $newStatus = $menu->status === 'active' ? 'inactive' : 'active';
        $menu->update(['status' => $newStatus, 'updated_by' => auth()->id()]);

        return back()->with('success', "Menu status updated to {$newStatus}.");
    }
}
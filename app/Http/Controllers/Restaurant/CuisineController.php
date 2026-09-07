<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Cuisine;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CuisineController extends Controller
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
     * Display active cuisines for selection by Restaurant Owner.
     */
    public function index(): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $activeCuisines = Cuisine::where('status', 'active')->orderBy('name', 'asc')->get();
        $assignedCuisineIds = $restaurant->cuisines()->pluck('cuisines.id')->toArray();

        return view('manage.restaurant.cuisines.index', compact('restaurant', 'activeCuisines', 'assignedCuisineIds'));
    }

    /**
     * Update/Sync selected cuisines for owner's restaurant.
     */
    public function update(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $request->validate([
            'cuisine_ids' => ['nullable', 'array'],
            'cuisine_ids.*' => ['exists:cuisines,id'],
        ]);

        $restaurant->cuisines()->sync($request->cuisine_ids ?? []);

        return redirect()->route('restaurant.cuisines.index')->with('success', 'Restaurant cuisines updated successfully.');
    }
}


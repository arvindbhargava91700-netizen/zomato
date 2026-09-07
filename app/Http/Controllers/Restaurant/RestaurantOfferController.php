<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RestaurantOfferController extends Controller
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

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'maximum_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = RestaurantOffer::where('restaurant_id', $restaurant->id);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $offers = $query->latest()->paginate(10)->withQueryString();

        return view('manage.restaurant.restaurant-offers.index', compact('offers', 'restaurant'));
    }

    public function create(): View
    {
        $restaurant = $this->getAssignedRestaurant();

        return view('manage.restaurant.restaurant-offers.create', compact('restaurant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $data = $request->validate($this->rules());
        $data['restaurant_id'] = $restaurant->id;
        $data['approval_status'] = 'pending';
        $data['admin_remarks'] = null;
        $data['approved_at'] = null;

        if ($request->hasFile('banner')) {
            $bannerName = time() . '_offer_' . Str::random(5) . '.' . $request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/restaurant-offers'), $bannerName);
            $data['banner'] = 'uploads/restaurant-offers/' . $bannerName;
        }

        RestaurantOffer::create($data);

        return redirect()->route('restaurant.restaurant-offers.index')
            ->with('success', 'Offer submitted for admin approval.');
    }

    public function edit(RestaurantOffer $restaurantOffer): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($restaurantOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        return view('manage.restaurant.restaurant-offers.edit', compact('restaurantOffer', 'restaurant'));
    }

    public function update(Request $request, RestaurantOffer $restaurantOffer): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($restaurantOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        $data = $request->validate($this->rules());

        if ($request->hasFile('banner')) {
            if ($restaurantOffer->banner && File::exists(public_path($restaurantOffer->banner))) {
                File::delete(public_path($restaurantOffer->banner));
            }
            $bannerName = time() . '_offer_' . Str::random(5) . '.' . $request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/restaurant-offers'), $bannerName);
            $data['banner'] = 'uploads/restaurant-offers/' . $bannerName;
        }

        $restaurantOffer->update($data);

        return redirect()->route('restaurant.restaurant-offers.index')
            ->with('success', 'Offer updated successfully.');
    }

    public function destroy(RestaurantOffer $restaurantOffer): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($restaurantOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        if ($restaurantOffer->banner && File::exists(public_path($restaurantOffer->banner))) {
            File::delete(public_path($restaurantOffer->banner));
        }

        $restaurantOffer->delete();

        return redirect()->route('restaurant.restaurant-offers.index')
            ->with('success', 'Offer deleted.');
    }

    public function toggleStatus(RestaurantOffer $restaurantOffer): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($restaurantOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        $restaurantOffer->update([
            'status' => $restaurantOffer->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Offer status updated.');
    }
}

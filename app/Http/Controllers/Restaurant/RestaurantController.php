<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Models\NightlifeBanner;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    /**
     * Get owner's assigned restaurant model or abort.
     */
    private function getAssignedRestaurant(): Restaurant
    {
        $owner = auth()->user();
        $restaurant = Restaurant::where('user_id', $owner?->id)->first();

        if (! $restaurant) {
            abort(403, 'No restaurant assigned to your account. Please contact administrator.');
        }

        return $restaurant;
    }

    /**
     * Display listing of owner's restaurant(s).
     */
    public function index(): View
    {
        $owner = auth()->user();
        $restaurants = Restaurant::with(['brand', 'nightlifeBanner'])->where('user_id', $owner?->id)->latest()->paginate(10);

        return view('manage.restaurant.restaurants.index', compact('restaurants'));
    }

    /**
     * Show form for creating restaurant for owner's account.
     */
    public function create(): View|RedirectResponse
    {
        $owner = auth()->user();
        $existing = Restaurant::where('user_id', $owner?->id)->first();

        if ($existing) {
            return redirect()->route('restaurant.restaurants.index')
                ->with('info', 'You already have a registered restaurant. Each vendor account is limited to 1 restaurant.');
        }

        $countries = Country::orderBy('name')->pluck('name', 'id');
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $nightlifeBanners = NightlifeBanner::where('status', 'active')->orderBy('title')->get();

        return view('manage.restaurant.restaurants.create', compact('countries', 'brands', 'nightlifeBanners'));
    }

    /**
     * Store a new restaurant for owner's account.
     */
    public function store(Request $request): RedirectResponse
    {
        $owner = auth()->user();

        if (Restaurant::where('user_id', $owner?->id)->exists()) {
            return redirect()->route('restaurant.restaurants.index')
                ->with('error', 'You already have a registered restaurant. Each vendor is allowed to create only 1 restaurant.');
        }

        $request->validate([
            'brand_id' => ['nullable', 'exists:brands,id'],
            'nightlife_banner_id' => ['nullable', 'exists:nightlife_banners,id'],
            'restaurant_type' => ['nullable', 'in:restaurant,brand,nightlife,cloud_kitchen'],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_slug' => ['nullable', 'string', 'max:255', Rule::unique('restaurants', 'restaurant_slug')],
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('restaurants', 'email')],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('restaurants', 'mobile')],
            'address' => ['required', 'string'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'opening_time' => ['nullable'],
            'closing_time' => ['nullable'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'delivery_radius' => ['nullable', 'numeric', 'min:0'],
            'estimated_delivery_time' => ['nullable', 'integer', 'min:0'],
            'commission_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'dining_commission_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'is_pure_veg' => ['nullable', 'boolean'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'fssai_number' => ['nullable', 'string', 'max:50'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'ifsc_code' => ['nullable', 'string', 'max:20'],
            'qr_code' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $data = $request->only(
            'brand_id', 'nightlife_banner_id', 'restaurant_type', 'restaurant_name', 'owner_name', 'email', 'mobile', 'address', 'postal_code',
            'country_id', 'state_id', 'city_id',
            'latitude', 'longitude', 'opening_time', 'closing_time', 'minimum_order_amount',
            'delivery_radius', 'estimated_delivery_time', 'commission_percentage', 'dining_commission_percentage', 'gst_number',
            'fssai_number', 'pan_number', 'bank_name', 'account_number', 'ifsc_code',
            'description', 'status'
        );

        $data['user_id'] = $owner->id;
        $data['restaurant_slug'] = $request->restaurant_slug ?? Str::slug($request->restaurant_name);
        $data['is_pure_veg'] = $request->boolean('is_pure_veg');
        $data['features'] = $request->input('features', []);
        $this->syncFeatureColumns($data);
        $data['approval_status'] = 'pending';
        $data['created_by'] = $owner->id;

        if ($request->hasFile('logo')) {
            $logoName = time().'_logo_'.Str::random(5).'.'.$request->file('logo')->extension();
            $request->file('logo')->move(public_path('uploads/restaurants/logos'), $logoName);
            $data['logo'] = 'uploads/restaurants/logos/'.$logoName;
        }

        if ($request->hasFile('banner')) {
            $bannerName = time().'_banner_'.Str::random(5).'.'.$request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/restaurants/banners'), $bannerName);
            $data['banner'] = 'uploads/restaurants/banners/'.$bannerName;
        }

        if ($request->hasFile('qr_code')) {
            $qrName = time().'_qr_'.Str::random(5).'.'.$request->file('qr_code')->extension();
            $request->file('qr_code')->move(public_path('uploads/restaurants/qr_codes'), $qrName);
            $data['qr_code'] = 'uploads/restaurants/qr_codes/'.$qrName;
        }

        Restaurant::create($data);

        return redirect()->route('restaurant.restaurants.index')->with('success', 'Restaurant created successfully.');
    }

    /**
     * Display owner's restaurant details.
     */
    public function show(Restaurant $restaurant): View
    {
        $this->authorizeOwner($restaurant);
        $restaurant->load('brand', 'nightlifeBanner');

        return view('manage.restaurant.restaurants.show', compact('restaurant'));
    }

    /**
     * Show edit form for owner's restaurant.
     */
    public function edit(Restaurant $restaurant): View
    {
        $this->authorizeOwner($restaurant);
        $countries = Country::orderBy('name')->pluck('name', 'id');
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $nightlifeBanners = NightlifeBanner::where('status', 'active')->orderBy('title')->get();

        return view('manage.restaurant.restaurants.edit', compact('restaurant', 'countries', 'brands', 'nightlifeBanners'));
    }

    /**
     * Update owner's restaurant.
     */
    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorizeOwner($restaurant);

        $request->validate([
            'brand_id' => ['nullable', 'exists:brands,id'],
            'nightlife_banner_id' => ['nullable', 'exists:nightlife_banners,id'],
            'restaurant_type' => ['nullable', 'in:restaurant,brand,nightlife,cloud_kitchen'],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_slug' => ['nullable', 'string', 'max:255', Rule::unique('restaurants', 'restaurant_slug')->ignore($restaurant->id)],
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('restaurants', 'email')->ignore($restaurant->id)],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('restaurants', 'mobile')->ignore($restaurant->id)],
            'address' => ['required', 'string'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'opening_time' => ['nullable'],
            'closing_time' => ['nullable'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'delivery_radius' => ['nullable', 'numeric', 'min:0'],
            'estimated_delivery_time' => ['nullable', 'integer', 'min:0'],
            'commission_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'dining_commission_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'is_pure_veg' => ['nullable', 'boolean'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'fssai_number' => ['nullable', 'string', 'max:50'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'ifsc_code' => ['nullable', 'string', 'max:20'],
            'qr_code' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $data = $request->only(
            'brand_id', 'nightlife_banner_id', 'restaurant_type', 'restaurant_name', 'owner_name', 'email', 'mobile', 'address', 'postal_code',
            'country_id', 'state_id', 'city_id',
            'latitude', 'longitude', 'opening_time', 'closing_time', 'minimum_order_amount',
            'delivery_radius', 'estimated_delivery_time', 'commission_percentage', 'dining_commission_percentage', 'gst_number',
            'fssai_number', 'pan_number', 'bank_name', 'account_number', 'ifsc_code',
            'description', 'status'
        );

        if (! empty($data['restaurant_slug']) && $data['restaurant_slug'] !== $restaurant->restaurant_slug) {
            $data['restaurant_slug'] = Str::slug($data['restaurant_slug']);
        } else {
            unset($data['restaurant_slug']);
        }

        $data['is_pure_veg'] = $request->boolean('is_pure_veg');
        $data['features'] = $request->input('features', []);
        $this->syncFeatureColumns($data);

        if ($request->hasFile('logo')) {
            if ($restaurant->logo && File::exists(public_path($restaurant->logo))) {
                File::delete(public_path($restaurant->logo));
            }
            $logoName = time().'_logo_'.Str::random(5).'.'.$request->file('logo')->extension();
            $request->file('logo')->move(public_path('uploads/restaurants/logos'), $logoName);
            $data['logo'] = 'uploads/restaurants/logos/'.$logoName;
        }

        if ($request->hasFile('banner')) {
            if ($restaurant->banner && File::exists(public_path($restaurant->banner))) {
                File::delete(public_path($restaurant->banner));
            }
            $bannerName = time().'_banner_'.Str::random(5).'.'.$request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/restaurants/banners'), $bannerName);
            $data['banner'] = 'uploads/restaurants/banners/'.$bannerName;
        }

        if ($request->hasFile('qr_code')) {
            if ($restaurant->qr_code && File::exists(public_path($restaurant->qr_code))) {
                File::delete(public_path($restaurant->qr_code));
            }
            $qrName = time().'_qr_'.Str::random(5).'.'.$request->file('qr_code')->extension();
            $request->file('qr_code')->move(public_path('uploads/restaurants/qr_codes'), $qrName);
            $data['qr_code'] = 'uploads/restaurants/qr_codes/'.$qrName;
        }

        $data['updated_by'] = auth()->id();

        $restaurant->update($data);

        return redirect()->route('restaurant.restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    /**
     * Keep the dedicated boolean feature columns in sync with the JSON features
     * values selected on the form (features: the restaurant_features IDs).
     */
    protected function syncFeatureColumns(array &$data): void
    {
        $featureIds = is_array($data['features'] ?? null) ? array_map('intval', $data['features']) : [];
        $slugs = \App\Models\RestaurantFeature::whereIn('id', $featureIds)->pluck('slug');

        $booleanColumns = [
            'is_pure_veg', 'credit_card', 'buffet', 'happy_hours', 'serves_alcohol',
            'pubs_bars', 'fine_dining', 'wifi', 'cafes', 'hygiene_rated',
            'online_bookings', 'outdoor_seating',
        ];

        foreach ($booleanColumns as $column) {
            $data[$column] = $slugs->contains($column) || $slugs->contains(str_replace('_', '-', $column));
        }
    }

    /**
     * Resubmit a rejected/pending restaurant for admin approval.
     */
    public function resubmit(Restaurant $restaurant): RedirectResponse
    {
        $this->authorizeOwner($restaurant);

        $restaurant->update([
            'approval_status' => 'pending',
            'admin_remarks' => null,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('restaurant.restaurants.edit', $restaurant->id)
            ->with('success', 'Your restaurant has been resubmitted for review. Please wait for admin approval.');
    }

    /**
     * Ensure the given restaurant belongs to the current owner.
     */
    private function authorizeOwner(Restaurant $restaurant): void
    {
        $owner = auth()->user();

        if ($restaurant->user_id !== $owner?->id) {
            abort(403, 'Unauthorized access to this restaurant.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Models\Brand;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    /**
     * Display a listing of restaurants with search and pagination.
     */
    public function index(Request $request): View
    {
        $query = Restaurant::with('brand');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('restaurant_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $restaurants = $query->latest()->paginate(10)->withQueryString();

        $pendingCount = Restaurant::where('approval_status', 'pending')->count();
        $approvedCount = Restaurant::where('approval_status', 'approved')->count();
        $rejectedCount = Restaurant::where('approval_status', 'rejected')->count();

        return view('manage.admin.restaurants.index', compact('restaurants', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Show the form for creating a new restaurant.
     */
    public function create(): View
    {
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        return view('manage.admin.restaurants.create', compact('brands'));
    }

    /**
     * Store a newly created restaurant in storage.
     */
    public function store(StoreRestaurantRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Generate unique slug if not provided
        $slug = $data['restaurant_slug'] ?? Str::slug($data['restaurant_name']);
        $originalSlug = $slug;
        $count = 1;
        while (Restaurant::where('restaurant_slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        $data['restaurant_slug'] = $slug;

        // File uploads
        if ($request->hasFile('logo')) {
            $logoName = time() . '_logo_' . Str::random(5) . '.' . $request->file('logo')->extension();
            $request->file('logo')->move(public_path('uploads/restaurants/logos'), $logoName);
            $data['logo'] = 'uploads/restaurants/logos/' . $logoName;
        }

        if ($request->hasFile('banner')) {
            $bannerName = time() . '_banner_' . Str::random(5) . '.' . $request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/restaurants/banners'), $bannerName);
            $data['banner'] = 'uploads/restaurants/banners/' . $bannerName;
        }

        if ($request->hasFile('qr_code')) {
            $qrName = time() . '_qr_' . Str::random(5) . '.' . $request->file('qr_code')->extension();
            $request->file('qr_code')->move(public_path('uploads/restaurants/qr_codes'), $qrName);
            $data['qr_code'] = 'uploads/restaurants/qr_codes/' . $qrName;
        }

        $data['is_pure_veg'] = $request->boolean('is_pure_veg');
        $data['pet_friendly'] = $request->boolean('pet_friendly');
        $data['outdoor_seating'] = $request->boolean('outdoor_seating');
        $data['serves_alcohol'] = $request->boolean('serves_alcohol');
        $data['features'] = $request->input('features', []);
        $this->syncFeatureColumns($data, $request);
        $data['created_by'] = Auth::guard('admin')->id();

        Restaurant::create($data);

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully.');
    }

    /**
     * Display the specified restaurant.
     */
    public function show(Restaurant $restaurant): View
    {
        $restaurant->load('brand');
        return view('manage.admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified restaurant.
     */
    public function edit(Restaurant $restaurant): View
    {
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        return view('manage.admin.restaurants.edit', compact('restaurant', 'brands'));
    }

    /**
     * Update the specified restaurant in storage.
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $data = $request->validated();

        // Slug handling
        if (!empty($data['restaurant_slug']) && $data['restaurant_slug'] !== $restaurant->restaurant_slug) {
            $data['restaurant_slug'] = Str::slug($data['restaurant_slug']);
        } else {
            unset($data['restaurant_slug']);
        }

        // Logo upload
        if ($request->hasFile('logo')) {
            if ($restaurant->logo && File::exists(public_path($restaurant->logo))) {
                File::delete(public_path($restaurant->logo));
            }
            $logoName = time() . '_logo_' . Str::random(5) . '.' . $request->file('logo')->extension();
            $request->file('logo')->move(public_path('uploads/restaurants/logos'), $logoName);
            $data['logo'] = 'uploads/restaurants/logos/' . $logoName;
        }

        // Banner upload
        if ($request->hasFile('banner')) {
            if ($restaurant->banner && File::exists(public_path($restaurant->banner))) {
                File::delete(public_path($restaurant->banner));
            }
            $bannerName = time() . '_banner_' . Str::random(5) . '.' . $request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/restaurants/banners'), $bannerName);
            $data['banner'] = 'uploads/restaurants/banners/' . $bannerName;
        }

        // QR Code upload
        if ($request->hasFile('qr_code')) {
            if ($restaurant->qr_code && File::exists(public_path($restaurant->qr_code))) {
                File::delete(public_path($restaurant->qr_code));
            }
            $qrName = time() . '_qr_' . Str::random(5) . '.' . $request->file('qr_code')->extension();
            $request->file('qr_code')->move(public_path('uploads/restaurants/qr_codes'), $qrName);
            $data['qr_code'] = 'uploads/restaurants/qr_codes/' . $qrName;
        }

        $data['is_pure_veg'] = $request->boolean('is_pure_veg');
        $data['pet_friendly'] = $request->boolean('pet_friendly');
        $data['outdoor_seating'] = $request->boolean('outdoor_seating');
        $data['serves_alcohol'] = $request->boolean('serves_alcohol');
        $data['features'] = $request->input('features', []);
        $this->syncFeatureColumns($data, $request);
        $data['updated_by'] = Auth::guard('admin')->id();

        $restaurant->update($data);

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant details updated successfully.');
    }

    /**
     * Keep the dedicated boolean feature columns in sync with the JSON features
     * values selected on the form (features: the restaurant_features IDs).
     */
    protected function syncFeatureColumns(array &$data, Request $request): void
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
     * Soft delete the specified restaurant.
     */
    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted (soft deleted) successfully.');
    }

    /**
     * Toggle status between active and inactive.
     */
    public function toggleStatus(Restaurant $restaurant): RedirectResponse
    {
        $newStatus = $restaurant->status === 'active' ? 'inactive' : 'active';
        $restaurant->update(['status' => $newStatus, 'updated_by' => Auth::guard('admin')->id()]);

        return back()->with('success', "Restaurant status updated to {$newStatus}.");
    }

    /**
     * Update the restaurant approval status and admin remarks.
     */
    public function updateApproval(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $request->validate([
            'approval_status' => ['required', 'in:approved,rejected,pending'],
            'admin_remarks' => ['nullable', 'string', 'max:1000', 'required_if:approval_status,rejected'],
        ]);

        $restaurant->update([
            'approval_status' => $request->approval_status,
            'admin_remarks' => $request->admin_remarks ?: null,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Restaurant approval status updated to {$request->approval_status}.");
    }
}

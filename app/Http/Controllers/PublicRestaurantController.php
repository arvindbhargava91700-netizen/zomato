<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Restaurant;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicRestaurantController extends Controller
{
    /**
     * Show a listing of approved (visible) restaurants.
     */
    public function index(Request $request): View
    {
        $query = Restaurant::visible();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('restaurant_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $restaurants = $query->latest()->paginate(12)->withQueryString();

        return view('manage.public.restaurants.index', compact('restaurants'));
    }

    /**
     * Show details of an approved restaurant.
     */
    public function show(Restaurant $restaurant): View
    {
        abort_unless($restaurant->approval_status === 'approved' && $restaurant->status === 'active', 404);

        return view('manage.public.restaurants.show', compact('restaurant'));
    }

    /**
     * Return approved (visible) restaurants near the given coordinates (JSON).
     * Sorted by distance using the Haversine formula. Falls back to latest
     * visible restaurants when coordinates are not provided.
     */
    public function nearby(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radius = (float) $request->get('radius', 50);

        $query = Restaurant::visible()->with('city');

        if (is_numeric($lat) && is_numeric($lng)) {
            $lat = (float) $lat;
            $lng = (float) $lng;

            $haversine = '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) '
                . '* cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))';

            $query->select('restaurants.*')
                ->selectRaw($haversine . ' as distance_km', [$lat, $lng, $lat])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance_km', '<=', $radius)
                ->orderBy('distance_km', 'asc');
        } else {
            $query->latest();
        }

        $query->withAvg('reviews as avg_rating', 'restaurant_rating');

        $query = $this->applyFilters($query, $request);

        $hasCoords = is_numeric($lat) && is_numeric($lng);
        $query = $this->applySort($query, $request, $hasCoords);

        $restaurants = $query->limit(12)->get();

        return response()->json([
            'success' => true,
            'restaurants' => $this->format($restaurants),
        ]);
    }

    /**
     * Apply Zomato-style filters to the restaurant query.
     */
    protected function applyFilters($query, Request $request)
    {
        // Open Now
        if ($request->boolean('open_now')) {
            $now = now()->format('H:i:s');
            $query->where(function ($q) use ($now) {
                // 24/7 (no timings set)
                $q->whereNull('opening_time')
                  ->orWhere(function ($q2) use ($now) {
                      // same-day hours
                      $q2->whereNotNull('opening_time')
                         ->whereNotNull('closing_time')
                         ->whereColumn('closing_time', '>', 'opening_time')
                         ->whereTime('opening_time', '<=', $now)
                         ->whereTime('closing_time', '>', $now);
                  })
                  ->orWhere(function ($q2) use ($now) {
                      // overnight hours (closes next day)
                      $q2->whereNotNull('opening_time')
                         ->whereNotNull('closing_time')
                         ->whereColumn('closing_time', '<', 'opening_time')
                         ->where(function ($q3) use ($now) {
                             $q3->whereTime('opening_time', '<=', $now)
                                ->orWhereTime('closing_time', '>', $now);
                         });
                  });
            });
        }

        // Has an active dining offer
        if ($request->boolean('offers')) {
            $query->whereHas('diningOffers', fn ($q) => $q->active());
        }

        // Minimum rating
        if ($request->filled('rating')) {
            $query->having('avg_rating', '>=', (float) $request->rating);
        }

        // Cuisines (many-to-many)
        $cuisines = $request->input('cuisines');
        if (is_array($cuisines)) {
            $cuisineIds = array_filter($cuisines, 'is_numeric');
        } elseif ($request->filled('cuisines')) {
            $cuisineIds = array_filter(explode(',', (string) $request->cuisines), 'is_numeric');
        } else {
            $cuisineIds = [];
        }
        if (!empty($cuisineIds)) {
            $query->whereHas('cuisines', fn ($q) => $q->whereIn('cuisines.id', $cuisineIds));
        }

        // Cost for two (based on the restaurant minimum order amount)
        if ($request->filled('min_cost')) {
            $query->where('minimum_order_amount', '>=', (float) $request->min_cost);
        }
        if ($request->filled('max_cost')) {
            $query->where('minimum_order_amount', '<=', (float) $request->max_cost);
        }

        // Amenities / features
        foreach ([
            'pet_friendly', 'outdoor_seating', 'serves_alcohol',
            'credit_card', 'buffet', 'happy_hours', 'pubs_bars', 'fine_dining',
            'wifi', 'cafes', 'hygiene_rated', 'online_bookings',
        ] as $amenity) {
            if ($request->boolean($amenity)) {
                $query->where($amenity, true);
            }
        }

        if ($request->boolean('pure_veg')) {
            $query->where('is_pure_veg', true);
        }

        return $query;
    }

    /**
     * Apply the selected sort order to the restaurant query.
     */
    protected function applySort($query, Request $request, bool $hasCoords)
    {
        switch ($request->input('sort', 'popularity')) {
            case 'rating_high':
                $query->orderBy('avg_rating', 'desc');
                break;
            case 'cost_low':
                $query->orderBy('minimum_order_amount', 'asc');
                break;
            case 'cost_high':
                $query->orderBy('minimum_order_amount', 'desc');
                break;
            case 'distance':
                if ($hasCoords) {
                    $query->orderBy('distance_km', 'asc');
                } else {
                    $query->latest();
                }
                break;
            default:
                $query->latest();
                break;
        }

        return $query;
    }

    /**
     * Suggest matching cities/states while typing a location (JSON).
     */
    public function locationSuggest(Request $request)
    {
        $q = trim((string) $request->get('q'));

        if ($q === '') {
            return response()->json(['suggestions' => []]);
        }

        $cities = City::where('name', 'like', $q . '%')
            ->orWhere('name', 'like', '%' . $q . '%')
            ->with('state')
            ->limit(8)
            ->get();

        $suggestions = $cities->map(function ($c) {
            return [
                'type' => 'city',
                'id' => $c->id,
                'name' => $c->name,
                'state' => $c->state ? $c->state->name : null,
            ];
        });

        return response()->json(['suggestions' => $suggestions]);
    }

    /**
     * Return approved (visible) restaurants filtered by a location name or id
     * (city / state / country). JSON.
     */
    public function searchLocation(Request $request)
    {
        $location = trim((string) $request->get('location'));
        $cityId = $request->get('city_id');
        $stateId = $request->get('state_id');
        $countryId = $request->get('country_id');

        $query = Restaurant::visible()->with('city');

        if ($cityId) {
            $query->where('city_id', $cityId);
        } elseif ($stateId) {
            $query->where('state_id', $stateId);
        } elseif ($countryId) {
            $query->where('country_id', $countryId);
        } elseif ($location !== '') {
            $city = City::where('name', $location)
                ->orWhere('name', 'like', $location . '%')
                ->first();

            if ($city) {
                $query->where('city_id', $city->id);
            } else {
                $state = State::where('name', $location)
                    ->orWhere('name', 'like', $location . '%')
                    ->first();

                if ($state) {
                    $query->where('state_id', $state->id);
                } else {
                    $country = Country::where('name', $location)
                        ->orWhere('name', 'like', $location . '%')
                        ->first();

                    if ($country) {
                        $query->where('country_id', $country->id);
                    } else {
                        // no matching location -> no results
                        $query->whereRaw('1 = 0');
                    }
                }
            }
        } else {
            $query->latest();
        }

        $query->withAvg('reviews as avg_rating', 'restaurant_rating');

        $query = $this->applyFilters($query, $request);

        $query = $this->applySort($query, $request, false);

        $restaurants = $query->limit(12)->get();

        return response()->json([
            'success' => true,
            'restaurants' => $this->format($restaurants),
        ]);
    }

    /**
     * Format restaurants for JSON responses.
     */
    protected function format($restaurants): array
    {
        $now = now();

        return $restaurants->map(function ($r) use ($now) {
            $isOpen = false;
            $open = $r->opening_time;
            $close = $r->closing_time;
            if ($open && $close) {
                if ($close->gt($open)) {
                    $isOpen = $now->gte($open) && $now->lt($close);
                } else {
                    // overnight hours (closes next day)
                    $isOpen = $now->gte($open) || $now->lt($close);
                }
            }

            return [
                'id' => $r->id,
                'name' => $r->restaurant_name,
                'slug' => $r->restaurant_slug,
                'logo' => $r->banner,
                'description' => $r->description,
                'address' => $r->address,
                'city' => $r->city ? $r->city->name : null,
                'distance_km' => $r->distance_km ?? null,
                'estimated_delivery_time' => $r->estimated_delivery_time,
                'cost_for_two' => $r->minimum_order_amount ? (float) $r->minimum_order_amount * 2 : null,
                'rating' => $r->avg_rating !== null ? round((float) $r->avg_rating, 1) : null,
                'opening_time' => $open ? $open->format('h:i A') : null,
                'closing_time' => $close ? $close->format('h:i A') : null,
                'is_open' => $isOpen,
            ];
        })->all();
    }
}

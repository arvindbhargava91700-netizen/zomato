<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Brand;
use App\Models\City;
use App\Models\NightlifeBanner;
use App\Models\CompanySetting;
use App\Models\Country;
use App\Models\Cuisine;
use App\Models\DiningOffer;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Income;
use App\Models\Order;
use App\Models\RestaurantOffer;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use App\Models\PromoCode;
use App\Models\Restaurant;
use App\Models\RestaurantBlog;
use App\Models\RestaurantMenu;
use App\Models\Review;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Models\EmailTemplate;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Wishlist;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class frontController extends Controller
{
    //

    public function profile()
    {
        return view('manage.front.profile');
    }

    public function myFeedback()
    {
        $reviews = \App\Models\Review::with(['restaurant', 'deliveryPartner', 'order'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('manage.front.my-feedback', compact('reviews'));
    }

    public function profileUpdateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        auth()->user()->update(['name' => $request->name]);

        return back()->with('success', 'Name updated successfully.');
    }

    public function profileUpdateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
        ]);

        auth()->user()->update(['email' => $request->email]);

        return back()->with('success', 'Email updated successfully.');
    }

    public function profileUpdatePhone(Request $request)
    {
        $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        auth()->user()->update(['phone' => $request->phone]);

        return back()->with('success', 'Phone number updated successfully.');
    }

    public function profileUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput()
                ->with('modal', 'password');
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function profileUpdateImage(Request $request)
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('profile_images');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);

            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                @unlink(public_path($user->profile_image));
            }

            $user->update(['profile_image' => 'profile_images/' . $filename]);
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/');
    }

    public function notificationUpdate(Request $request)
    {
        auth()->user()->update([
            'notify_offer' => $request->has('notify_offer'),
            'notify_order' => $request->has('notify_order'),
            'notify_new' => $request->has('notify_new'),
        ]);

        return back()->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Helper to retrieve active, approved dining offers filtered by location.
     */
    protected function getFilteredDiningOffers(Request $request)
    {
        $query = DiningOffer::live()
            ->whereHas('restaurant', function ($q) {
                $q->where('approval_status', 'approved')->where('status', 'active');
            })
            ->with(['restaurant.city', 'restaurant.state']);

        $location = trim((string) $request->get('location'));
        $cityId = $request->get('city_id');
        $stateId = $request->get('state_id');
        $countryId = $request->get('country_id');
        $locationId = $request->get('location_id');
        $locationType = $request->get('location_type');

        if ($locationType && $locationId && $locationId !== 'all') {
            if ($locationType === 'city') {
                $cityId = $locationId;
            } elseif ($locationType === 'state') {
                $stateId = $locationId;
            } elseif ($locationType === 'country') {
                $countryId = $locationId;
            }
        }

        if ($cityId) {
            $query->whereHas('restaurant', fn($q) => $q->where('city_id', $cityId));
        } elseif ($stateId) {
            $query->whereHas('restaurant', fn($q) => $q->where('state_id', $stateId));
        } elseif ($countryId) {
            $query->whereHas('restaurant', fn($q) => $q->where('country_id', $countryId));
        } elseif ($location !== '' && strtolower($location) !== 'all') {
            $city = City::where('name', $location)
                ->orWhere('name', 'like', $location . '%')
                ->first();

            if ($city) {
                $query->whereHas('restaurant', fn($q) => $q->where('city_id', $city->id));
            } else {
                $state = State::where('name', $location)
                    ->orWhere('name', 'like', $location . '%')
                    ->first();

                if ($state) {
                    $query->whereHas('restaurant', fn($q) => $q->where('state_id', $state->id));
                } else {
                    $country = Country::where('name', $location)
                        ->orWhere('name', 'like', $location . '%')
                        ->first();

                    if ($country) {
                        $query->whereHas('restaurant', fn($q) => $q->where('country_id', $country->id));
                    } else {
                        // Look for partial match on restaurant address or city
                        $query->whereHas('restaurant', function ($q) use ($location) {
                            $q->where('address', 'like', "%{$location}%")
                              ->orWhereHas('city', fn($c) => $c->where('name', 'like', "%{$location}%"));
                        });
                    }
                }
            }
        }

        // Coordinates filter if available
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        if (is_numeric($lat) && is_numeric($lng) && !$cityId && !$stateId && !$countryId && $location === '') {
            $radius = (float) $request->get('radius', 50);
            $haversine = '(6371 * acos(cos(radians(' . (float)$lat . ')) * cos(radians(latitude)) '
                . '* cos(radians(longitude) - radians(' . (float)$lng . ')) + sin(radians(' . (float)$lat . ')) * sin(radians(latitude))))';

            $query->whereHas('restaurant', function ($q) use ($haversine, $radius) {
                $q->whereNotNull('latitude')
                  ->whereNotNull('longitude')
                  ->whereRaw("{$haversine} <= ?", [$radius]);
            });
        }

        return $query->latest()->take(12)->get();
    }

    public function index(Request $request)
    {
        $categories = FoodCategory::where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $cuisines = Cuisine::where('status', 'active')
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', 'active')
            ->latest()
            ->get();

        $collections = NightlifeBanner::withCount('restaurants')
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $deals = $this->getFilteredDiningOffers($request);

        $locationId = $request->query('location_id') ?? $request->query('city_id');
        $locationType = $request->query('location_type') ?? ($locationId ? 'city' : null);
        $location = trim((string) $request->get('location'));
        if ($location === '' && $deals->isNotEmpty() && $deals->first()->restaurant?->city) {
            $location = $deals->first()->restaurant->city->name;
        } elseif ($location === '') {
            $location = 'Lucknow';
        }

        return view('manage.front.index', compact('categories', 'cuisines', 'brands', 'deals', 'collections'))
            ->with('location_id', $locationId)
            ->with('location_type', $locationType)
            ->with('location', $location);
    }

    /**
     * AJAX endpoint: Return active, approved dining offers filtered by location.
     */
    public function diningOffersByLocation(Request $request)
    {
        $deals = $this->getFilteredDiningOffers($request);

        $formatted = $deals->map(function ($deal) {
            $restaurant = $deal->restaurant;
            $banner = $restaurant?->banner ? asset($restaurant->banner) : ($restaurant?->logo ? asset($restaurant->logo) : asset('front/assets/images/banner/banner1.jpg'));

            $validity = 'Always Active';
            if ($deal->start_date && $deal->end_date) {
                $validity = $deal->start_date->format('d M Y') . ' – ' . $deal->end_date->format('d M Y');
            } elseif ($deal->start_date) {
                $validity = 'From ' . $deal->start_date->format('d M Y');
            } elseif ($deal->end_date) {
                $validity = 'Valid till ' . $deal->end_date->format('d M Y');
            }

            $discountDisplay = $deal->discount_type === 'percentage'
                ? rtrim(rtrim(number_format((float)$deal->discount_value, 2), '0'), '.') . '% OFF'
                : '₹' . number_format((float)$deal->discount_value, 2) . ' OFF';

            $cityName = $restaurant?->city?->name ?? 'Lucknow';
            $diningOutUrl = route('dining.out', ['location' => $cityName, 'offers' => 1]);

            return [
                'id' => $deal->id,
                'title' => $deal->title,
                'coupon_code' => $deal->coupon_code,
                'discount_type' => $deal->discount_type,
                'discount_value' => (float) $deal->discount_value,
                'discount_display' => $discountDisplay,
                'cover_charge' => $deal->cover_charge ? (float) $deal->cover_charge : null,
                'min_bill_amount' => $deal->min_bill_amount ? (float) $deal->min_bill_amount : null,
                'max_discount_amount' => $deal->max_discount_amount ? (float) $deal->max_discount_amount : null,
                'validity' => $validity,
                'banner_url' => $banner,
                'dining_out_url' => $diningOutUrl,
                'restaurant' => $restaurant ? [
                    'id' => $restaurant->id,
                    'name' => $restaurant->restaurant_name,
                    'slug' => $restaurant->restaurant_slug,
                    'city' => $restaurant->city?->name,
                    'address' => $restaurant->address,
                    'url' => route('public.restaurants.show', $restaurant->restaurant_slug),
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'deals' => $formatted,
            'count' => $formatted->count(),
        ]);
    }

    /**
     * Zomato-style Dining Out page: Shows all restaurants in a selected location (e.g. Lucknow)
     * with exact same design and filters as index page.
     */
    public function diningOutListing(Request $request)
    {
        $categories = FoodCategory::where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $cuisines = Cuisine::where('status', 'active')
            ->orderBy('name')
            ->get();

        $deals = $this->getFilteredDiningOffers($request);

        $locationId = $request->query('location_id') ?? $request->query('city_id');
        $locationType = $request->query('location_type') ?? ($locationId ? 'city' : null);
        $location = trim((string) $request->get('location'));
        if ($location === '' && $deals->isNotEmpty() && $deals->first()->restaurant?->city) {
            $location = $deals->first()->restaurant->city->name;
        } elseif ($location === '') {
            $location = 'Lucknow';
        }

        $presetOffers = $request->has('offers') ? $request->boolean('offers') : true;

        return view('manage.front.diningOut', compact('categories', 'cuisines', 'deals', 'presetOffers'))
            ->with('location_id', $locationId)
            ->with('location_type', $locationType)
            ->with('location', $location);
    }

    public function menuListing(Request $request)
    {
        $slug = $request->query('slug') ?: $request->route('slug');
        $brandSlug = $request->query('brand');
        $restaurantId = $request->query('restaurant_id') ?: $request->query('id');
        $location = $request->query('location') ?: $request->query('city') ?: session('selected_location') ?: 'Lucknow';

        $query = Restaurant::with([
            'brand',
            'city',
            'state',
            'cuisines',
            'diningOffers' => function ($q) {
                $q->live()->orderBy('discount_value', 'desc');
            },
            'reviews' => function ($q) {
                $q->with('user')->orderBy('id', 'desc');
            },
        ]);

        $restaurant = null;

        // 1. If explicit restaurant slug provided (e.g. clicked on a restaurant card)
        if ($slug) {
            $restaurant = (clone $query)->where('restaurant_slug', $slug)->first();
            if (!$restaurant) {
                $restaurant = (clone $query)->where('id', $slug)->first();
            }
        }

        // 2. If direct restaurant ID provided
        if (!$restaurant && $restaurantId) {
            $restaurant = (clone $query)->where('id', $restaurantId)->first();
        }

        // 3. If Brand clicked (e.g. 'la-pinoz', 'mcdonalds', etc.)
        if (!$restaurant && $brandSlug) {
            // First priority: Brand outlet in the target location (e.g. Lucknow)
            $restaurant = (clone $query)
                ->where(function ($q) use ($brandSlug) {
                    $q->whereHas('brand', function ($b) use ($brandSlug) {
                        $b->where('slug', $brandSlug)
                          ->orWhere('name', 'like', "%{$brandSlug}%");
                    })
                    ->orWhere('restaurant_slug', 'like', "%{$brandSlug}%")
                    ->orWhere('restaurant_name', 'like', "%{$brandSlug}%");
                })
                ->where(function ($q) use ($location) {
                    $q->whereHas('city', function ($c) use ($location) {
                        $c->where('name', 'like', "%{$location}%");
                    })
                    ->orWhere('address', 'like', "%{$location}%")
                    ->orWhere('restaurant_slug', 'like', "%{$location}%");
                })
                ->first();

            // Second priority: Any outlet of that Brand
            if (!$restaurant) {
                $restaurant = (clone $query)
                    ->where(function ($q) use ($brandSlug) {
                        $q->whereHas('brand', function ($b) use ($brandSlug) {
                            $b->where('slug', $brandSlug)
                              ->orWhere('name', 'like', "%{$brandSlug}%");
                        })
                        ->orWhere('restaurant_slug', 'like', "%{$brandSlug}%")
                        ->orWhere('restaurant_name', 'like', "%{$brandSlug}%");
                    })
                    ->first();
            }
        }

        // 4. Default / Fallback: Restaurant in location or first available restaurant
        if (!$restaurant) {
            if ($location) {
                $restaurant = (clone $query)
                    ->where(function ($q) use ($location) {
                        $q->whereHas('city', function ($c) use ($location) {
                            $c->where('name', 'like', "%{$location}%");
                        })
                        ->orWhere('address', 'like', "%{$location}%");
                    })
                    ->first();
            }

            if (!$restaurant) {
                $restaurant = $query->first();
            }
        }

        // 5. Populate categories & menu foods belonging to this restaurant
        if ($restaurant) {
            $foodCategories = FoodCategory::where('status', 'active')
                ->whereHas('foods', function ($fq) use ($restaurant) {
                    $fq->where('restaurant_id', $restaurant->id)->where('status', 'active');
                })
                ->with([
                    'foods' => function ($fq) use ($restaurant) {
                        $fq->where('restaurant_id', $restaurant->id)
                           ->where('status', 'active')
                           ->orderBy('sort_order', 'asc')
                           ->with(['variants' => function ($vq) {
                               $vq->where('status', 'active')->orderBy('sort_order', 'asc');
                           }]);
                    }
                ])
                ->orderBy('sort_order', 'asc')
                ->get();

            $ownCategories = FoodCategory::where('status', 'active')
                ->where('restaurant_id', $restaurant->id)
                ->whereNotIn('id', $foodCategories->pluck('id'))
                ->with([
                    'foods' => function ($fq) use ($restaurant) {
                        $fq->where('restaurant_id', $restaurant->id)
                           ->where('status', 'active')
                           ->orderBy('sort_order', 'asc')
                           ->with(['variants' => function ($vq) {
                               $vq->where('status', 'active')->orderBy('sort_order', 'asc');
                           }]);
                    }
                ])
                ->orderBy('sort_order', 'asc')
                ->get();

            $allCategories = $foodCategories->concat($ownCategories)->sortBy('sort_order')->values();

            $restaurant->setRelation('categories', $allCategories);

            $menus = RestaurantMenu::where('restaurant_id', $restaurant->id)
                ->where('status', 'active')
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $restaurant->setRelation('menus', $menus);
        }

        return view('manage.front.menuListing', compact('restaurant'));
    }

    public function bookTableStore(Request $request)
    {
        $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'book_date' => ['required', 'date'],
            'book_time' => ['required'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'dining_offer_id' => ['nullable', 'exists:dining_offers,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'razorpay_payment_id' => ['nullable', 'string', 'max:100'],
            'razorpay_order_id' => ['nullable', 'string', 'max:100'],
            'razorpay_signature' => ['nullable', 'string', 'max:255'],
        ]);

        $restaurant = Restaurant::findOrFail($request->restaurant_id);

        $offer = null;
        $coverCharge = 0;
        if ($request->filled('dining_offer_id')) {
            $offer = DiningOffer::where('restaurant_id', $restaurant->id)
                ->where('id', $request->dining_offer_id)
                ->first();
            if ($offer && $offer->cover_charge) {
                $coverCharge = (float) $offer->cover_charge;
            }
        }

        $paymentMethod = $request->input('payment_method', 'razorpay');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        $isPaid = false;
        $note = $request->note ?? null;

        if ($coverCharge > 0) {
            $isPaid = true;
            $payRef = $razorpayPaymentId ? " (Payment ID: {$razorpayPaymentId})" : '';
            $note = "Cover charge of ₹" . number_format($coverCharge, 2) . " paid via " . strtoupper($paymentMethod) . "{$payRef} at the time of booking.";
        }

        // Auto-allocate best available table matching guest capacity
        $slotInfo = $restaurant->getSlotAvailability($request->book_date, $request->book_time, (int) $request->guests);
        $assignedTableId = null;
        if (!empty($slotInfo['suitable_tables']) && count($slotInfo['suitable_tables']) > 0) {
            $assignedTableId = $slotInfo['suitable_tables'][0]->id ?? $slotInfo['suitable_tables'][0]['id'] ?? null;
        } elseif (!empty($slotInfo['free_tables']) && count($slotInfo['free_tables']) > 0) {
            $assignedTableId = $slotInfo['free_tables'][0]->id ?? $slotInfo['free_tables'][0]['id'] ?? null;
        }

        $booking = Booking::create([
            'restaurant_id' => $restaurant->id,
            'dining_offer_id' => $offer?->id,
            'restaurant_table_id' => $assignedTableId,
            'customer_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'book_date' => $request->book_date,
            'book_time' => $request->book_time,
            'guests' => (int) $request->guests,
            'cover_charge' => $coverCharge,
            'payment_method' => $paymentMethod,
            'payment_status' => $isPaid ? 'paid' : 'pending',
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_signature' => $razorpaySignature,
            'status' => $coverCharge > 0 ? Booking::STATUS_ACCEPTED : Booking::STATUS_PENDING,
            'note' => $note,
        ]);

        $transaction = null;
        if ($coverCharge > 0) {
            // Fetch cover charge share percentages configured in Admin Company Settings
            $companySetting = CompanySetting::firstSetting();
            $adminSharePct = $companySetting->coverChargeAdminShare(); // Default: 20.00%
            $restaurantSharePct = $companySetting->coverChargeRestaurantShare(); // Default: 80.00%

            // Calculate precise share distribution
            $adminAmount = round(($coverCharge * $adminSharePct) / 100, 2);
            $restaurantAmount = round($coverCharge - $adminAmount, 2);

            // 1. Create Transaction Detail Record
            $txnNumber = Transaction::generateTransactionNumber('TBL');
            $transaction = Transaction::create([
                'transaction_number' => $txnNumber,
                'type' => Transaction::TYPE_TABLE_BOOKING,
                'booking_id' => $booking->id,
                'restaurant_id' => $restaurant->id,
                'customer_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'book_date' => $request->book_date,
                'book_time' => $request->book_time,
                'guests' => (int) $request->guests,
                'total_amount' => $coverCharge,
                'admin_share_percent' => $adminSharePct,
                'admin_amount' => $adminAmount,
                'restaurant_share_percent' => $restaurantSharePct,
                'restaurant_amount' => $restaurantAmount,
                'payment_method' => $paymentMethod,
                'payment_gateway' => 'razorpay',
                'payment_id' => $razorpayPaymentId,
                'order_reference_id' => $razorpayOrderId,
                'signature' => $razorpaySignature,
                'payment_status' => Transaction::PAYMENT_STATUS_PAID,
                'status' => Transaction::STATUS_SUCCESS,
                'note' => $note,
                'meta_data' => [
                    'offer_id' => $offer?->id,
                    'offer_title' => $offer?->title,
                    'assigned_table_id' => $assignedTableId,
                    'admin_share_pct' => $adminSharePct,
                    'restaurant_share_pct' => $restaurantSharePct,
                ],
                'paid_at' => now(),
            ]);

            // 2. Distribute into Incomes Ledger (Admin / Platform Share)
            Income::create([
                'booking_id' => $booking->id,
                'restaurant_id' => $restaurant->id,
                'income_type' => Income::TYPE_COVER_CHARGE_PAYMENT,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'base_amount' => $coverCharge,
                'percentage' => $adminSharePct,
                'amount' => $adminAmount,
                'paid_at' => now(),
            ]);

            // 3. Distribute into Incomes Ledger (Restaurant Owner Share)
            Income::create([
                'booking_id' => $booking->id,
                'restaurant_id' => $restaurant->id,
                'income_type' => Income::TYPE_COVER_CHARGE_PAYMENT,
                'recipient_type' => Income::RECIPIENT_RESTAURANT,
                'recipient_id' => $restaurant->user_id,
                'user_id' => $restaurant->user_id,
                'base_amount' => $coverCharge,
                'percentage' => $restaurantSharePct,
                'amount' => $restaurantAmount,
                'paid_at' => now(),
            ]);

            // 4. Credit Restaurant Owner Wallet
            if ($restaurant->user_id && $restaurantAmount > 0) {
                $restaurantOwner = User::find($restaurant->user_id);
                if ($restaurantOwner) {
                    $restaurantOwner->getOrCreateWallet()->credit(
                        $restaurantAmount,
                        'table_booking_share',
                        $booking->id,
                        "Cover charge payout for Table Booking #{$booking->id} at {$restaurant->restaurant_name}"
                    );
                }
            }

            // Audit log
            AuditLog::log(
                'Table Booking Payment',
                'completed',
                null,
                [
                    'booking_id' => $booking->id,
                    'transaction_number' => $txnNumber,
                    'cover_charge' => $coverCharge,
                    'admin_amount' => $adminAmount,
                    'restaurant_amount' => $restaurantAmount,
                ],
                "Cover charge of ₹{$coverCharge} received for Booking #{$booking->id}. Distributed: Admin ₹{$adminAmount} ({$adminSharePct}%), Restaurant ₹{$restaurantAmount} ({$restaurantSharePct}%)."
            );
        }

        $currencySymbol = '₹';
        $message = $coverCharge > 0
            ? "Payment of {$currencySymbol}" . number_format($coverCharge, 2) . " via Razorpay successful! Your table is confirmed."
            : "Your table booking request has been submitted successfully!";

        return response()->json([
            'success' => true,
            'booking_id' => $booking->id,
            'transaction_id' => $transaction?->id,
            'transaction_number' => $transaction?->transaction_number,
            'cover_charge' => $coverCharge,
            'payment_method' => $paymentMethod,
            'razorpay_payment_id' => $razorpayPaymentId,
            'table_id' => $assignedTableId,
            'status' => $booking->status,
            'message' => $message,
        ]);
    }

    /**
     * Create Razorpay Order for Table Booking Cover Charge.
     */
    public function createRazorpayOrder(Request $request)
    {
        $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $restaurant = Restaurant::findOrFail($request->restaurant_id);
        $amountInPaise = (int) round(((float) $request->amount) * 100);
        $receipt = 'tbl_' . time() . '_' . rand(100, 999);

        $apiKey = PaymentGateway::getRazorpayKey();
        $apiSecret = PaymentGateway::getRazorpaySecret();
        $gw = PaymentGateway::getGateway(PaymentGateway::GATEWAY_RAZORPAY);
        $currency = $gw?->currency ?: 'INR';
        $themeColor = $gw?->theme_color ?: '#072654';

        $orderId = null;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'amount' => $amountInPaise,
                'currency' => $currency,
                'receipt' => $receipt,
                'payment_capture' => 1,
                'notes' => [
                    'restaurant_id' => $restaurant->id,
                    'restaurant_name' => $restaurant->restaurant_name ?? $restaurant->name,
                ],
            ]));
            curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':' . $apiSecret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 || $httpCode === 201) {
                $orderData = json_decode($response, true);
                $orderId = $orderData['id'] ?? null;
            }
        } catch (\Throwable $e) {
            \Log::warning('Razorpay Order API call failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'key' => $apiKey,
            'order_id' => $orderId,
            'amount' => $amountInPaise,
            'currency' => $currency,
            'theme_color' => $themeColor,
            'restaurant_name' => $restaurant->restaurant_name ?? $restaurant->name,
            'description' => 'Table Reservation Cover Charge - ' . ($restaurant->restaurant_name ?? $restaurant->name),
        ]);
    }



    /**
     * Get dynamic dining slots and availability for customer booking.
     */
    public function getDiningSlots(Request $request, Restaurant $restaurant)
    {
        $date = $request->query('date', \Carbon\Carbon::today()->toDateString());
        $guests = (int) $request->query('guests', 1);

        $slots = $restaurant->generateTimeSlots($date);
        $matrix = [];

        foreach ($slots as $slot) {
            $availability = $restaurant->getSlotAvailability($date, $slot['time'], $guests);
            $matrix[] = array_merge($slot, $availability);
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'guests' => $guests,
            'slot_duration' => (int) ($restaurant->slot_duration_minutes ?: 60),
            'advance_days' => (int) ($restaurant->advance_booking_days ?: 7),
            'slots' => $matrix,
        ]);
    }



    /**
     * Customer Front: Blog List (Live & Approved Blogs Only).
     */
    public function blogListing(Request $request)
    {
        $query = RestaurantBlog::published()->with(['restaurant', 'author', 'category', 'cuisine']);

        if ($request->filled('category_id')) {
            $query->where('food_category_id', $request->category_id);
        } elseif ($request->filled('category')) {
            $catSearch = $request->category;
            $query->whereHas('category', function ($q) use ($catSearch) {
                $q->where('name', 'like', "%{$catSearch}%");
            });
        }

        if ($request->filled('cuisine_id')) {
            $query->where('cuisine_id', $request->cuisine_id);
        } elseif ($request->filled('cuisine')) {
            $cuiSearch = $request->cuisine;
            $query->whereHas('cuisine', function ($q) use ($cuiSearch) {
                $q->where('name', 'like', "%{$cuiSearch}%");
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($qc) use ($search) {
                      $qc->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('cuisine', function ($qcui) use ($search) {
                      $qcui->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('restaurant', function ($q2) use ($search) {
                      $q2->where('restaurant_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        $blogs = $query->latest()->paginate(9)->withQueryString();
        $recentBlogs = RestaurantBlog::published()->with(['restaurant', 'author', 'category', 'cuisine'])->latest()->take(4)->get();
        $featuredRestaurants = Restaurant::has('blogs')->take(6)->get();
        $sidebarCategories = FoodCategory::where('status', 'active')
            ->withCount(['blogs' => fn($q) => $q->published()])
            ->withCount('foods')
            ->orderBy('name')
            ->take(15)
            ->get();
        $sidebarTags = Cuisine::where('status', 'active')->orderBy('name')->take(8)->get();

        return view('manage.front.blogListing', compact('blogs', 'recentBlogs', 'featuredRestaurants', 'sidebarCategories', 'sidebarTags'));
    }

    /**
     * Customer Front: Blog Details (Live & Approved).
     */
    public function blogDetails(Request $request, ?string $slug = null)
    {
        $blogSlug = $slug ?? $request->query('slug');

        if ($blogSlug) {
            $blog = RestaurantBlog::published()
                ->with(['restaurant', 'author', 'category', 'cuisine', 'rootComments.replies.user', 'rootComments.user'])
                ->where('slug', $blogSlug)
                ->first();
        } else {
            $blog = RestaurantBlog::published()
                ->with(['restaurant', 'author', 'category', 'cuisine', 'rootComments.replies.user', 'rootComments.user'])
                ->latest()
                ->first();
        }

        if (!$blog) {
            return redirect()->route('blog.list')->with('error', 'Blog article not found or not published yet.');
        }

        $blog->increment('views_count');

        $userReaction = $blog->getCurrentUserReaction();
        $recentBlogs = RestaurantBlog::published()->with(['restaurant', 'author', 'category', 'cuisine'])->where('id', '!=', $blog->id)->latest()->take(4)->get();
        $moreFromRestaurant = RestaurantBlog::published()->where('restaurant_id', $blog->restaurant_id)->where('id', '!=', $blog->id)->take(3)->get();
        $featuredRestaurants = Restaurant::has('blogs')->take(6)->get();
        $sidebarCategories = FoodCategory::where('status', 'active')
            ->withCount(['blogs' => fn($q) => $q->published()])
            ->withCount('foods')
            ->orderBy('name')
            ->take(15)
            ->get();
        $sidebarTags = Cuisine::where('status', 'active')->orderBy('name')->take(8)->get();

        return view('manage.front.blogDetails', compact('blog', 'userReaction', 'recentBlogs', 'moreFromRestaurant', 'featuredRestaurants', 'sidebarCategories', 'sidebarTags'));
    }





    /**
     * Show all foods belonging to a given (dynamically browsed) category.
     */
    public function categoryFoods(Request $request)
    {
        $category = FoodCategory::where('status', 'active')
            ->findOrFail($request->query('category_id'));

        $categories = FoodCategory::where('status', 'active')
            ->orderBy('name')
            ->get();

        $cuisines = Cuisine::where('status', 'active')
            ->orderBy('name')
            ->get();

        $foods = Food::with(['restaurant', 'variants'])
            ->where('food_category_id', $category->id)
            ->where('status', 'active');

        if ($request->filled('veg')) {
            $foods->whereHas('restaurant', function ($q) {
                $q->where('is_pure_veg', 1);
            });
        }

        if ($request->filled('cuisine')) {
            $cuisineId = $request->query('cuisine');
            $foods->whereHas('restaurant', function ($q) use ($cuisineId) {
                $q->whereHas('cuisines', function ($q2) use ($cuisineId) {
                    $q2->where('cuisines.id', $cuisineId);
                });
            });
        }

        $foods = $foods->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('manage.front.categoryFoods', compact('category', 'categories', 'cuisines', 'foods'));
    }

    public function faqList(){

        return view('manage.front.faqList');
    }
    public function testomonialList(){
        return view('manage.front.testomonialList');
    }
    public function wishList()
    {
        $wishlists = Wishlist::with([
            'food',
            'food.restaurant' => function ($q) {
                $q->withAvg('reviews', 'restaurant_rating')->with('city');
            },
            'restaurant' => function ($q) {
                $q->withAvg('reviews', 'restaurant_rating')->with('city');
            },
        ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('manage.front.wishList', compact('wishlists'));
    }

    public function wishlistDestroy($id)
    {
        $item = Wishlist::where('user_id', auth()->id())->findOrFail($id);
        $item->delete();

        return back()->with('success', 'Item removed from your wishlist.');
    }

    /**
     * Toggle a restaurant (or food) in the customer's wishlist.
     * Adds it if missing, removes it if already saved. Returns JSON so the
     * menu/storefront pages can update the heart icon and show a toast.
     */
    public function wishlistStore(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'food_id' => ['nullable', 'exists:foods,id'],
        ]);

        $query = Wishlist::where('user_id', auth()->id())
            ->where('restaurant_id', $data['restaurant_id']);

        if (!empty($data['food_id'])) {
            $query->where('food_id', $data['food_id']);
        } else {
            $query->whereNull('food_id');
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = 'Removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'restaurant_id' => $data['restaurant_id'],
                'food_id' => $data['food_id'] ?? null,
            ]);
            $added = true;
            $message = 'Added to wishlist.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
    public function contact()
    {
        $company = CompanySetting::firstSetting();
        $contactInfo = [
            'phone' => $company?->phone ?: (Setting::where('key', 'support_phone')->value('value') ?: '+1 (800) 456-7890'),
            'email' => $company?->email ?: (Setting::where('key', 'mail_from_address')->value('value') ?: 'support@foodexpress.com'),
            'headquarters' => $company?->address ?: '742 Evergreen Terrace, Gourmet District, NY 10001',
            'hours' => 'Mon - Sun: 08:00 AM - 11:00 PM',
        ];

        return view('manage.front.contact', compact('contactInfo'));
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:5', 'max:3000'],
        ]);

        $contactMessage = ContactMessage::create([
            'user_id' => auth()->id(),
            'first_name' => trim($validated['first_name']),
            'last_name' => !empty($validated['last_name']) ? trim($validated['last_name']) : null,
            'email' => trim($validated['email']),
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'subject' => !empty($validated['subject']) ? trim($validated['subject']) : 'General Inquiry',
            'message' => trim($validated['message']),
            'status' => 'unread',
            'ip_address' => $request->ip(),
        ]);

        // Dispatch customer acknowledgment email with dynamic header and footer
        try {
            $company = CompanySetting::firstSetting();
            $appName = $company?->company_name ?: (Setting::get('mail_from_name') ?: config('app.name', 'Food Express'));
            $fromEmail = Setting::get('mail_from_address') ?: config('mail.from.address', 'support@foodexpress.com');
            $subject = "We received your message: " . ($contactMessage->subject ?: 'General Inquiry') . " - {$appName}";

            $bodyHtml = '
                <p style="font-size:16px;margin-top:0;">Dear <strong>' . htmlspecialchars($contactMessage->full_name) . '</strong>,</p>
                <p>Thank you for getting in touch with us! We have successfully received your inquiry regarding <strong>"' . htmlspecialchars($contactMessage->subject ?: 'General Inquiry') . '"</strong>.</p>
                <p>Our customer support team is reviewing your message and will respond to you shortly.</p>
                
                <div style="background:#f8fafc;border-left:4px solid #ff8d2f;padding:14px 18px;margin:20px 0;border-radius:0 8px 8px 0;">
                    <div style="font-size:11.5px;color:#64748b;font-weight:bold;text-transform:uppercase;margin-bottom:4px;">Your Inquiry:</div>
                    <div style="color:#334155;font-style:italic;">"' . nl2br(htmlspecialchars($contactMessage->message)) . '"</div>
                </div>

                <p style="margin-bottom:0;">Warm regards,<br><strong>' . htmlspecialchars($appName) . ' Support Team</strong></p>
            ';

            $fullEmailHtml = EmailTemplate::renderWithLayout($bodyHtml, [
                'name' => $contactMessage->full_name,
                'first_name' => $contactMessage->first_name,
                'email' => $contactMessage->email,
                'subject' => $contactMessage->subject ?: 'General Inquiry',
            ]);

            Mail::html($fullEmailHtml, function ($msg) use ($contactMessage, $subject, $fromEmail, $appName) {
                $msg->to($contactMessage->email, $contactMessage->full_name)
                    ->subject($subject)
                    ->from($fromEmail, $appName);
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Contact customer acknowledgment email dispatch failed: ' . $e->getMessage());
        }

        $successMessage = 'Thank you! Your message has been received. Our team will get in touch with you shortly.';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'data' => $contactMessage,
            ]);
        }

        return redirect()->route('contact')->with('success', $successMessage);
    }
    
    public function myOrders()
    {
        $orders = Order::with('restaurant')->where('user_id', auth()->user()->id)->latest()->paginate(10);
        $taxPercentage = Setting::where('key', 'tax_percentage')->value('value') ?? 0;
        $currencySymbol = Setting::where('key', 'currency_symbol')->value('value') ?? '$';
        $taxGst = Setting::where('key', 'tax_gst')->value('value') ?? null;
        
        return view('manage.front.myOrders', compact('orders', 'taxPercentage', 'currencySymbol', 'taxGst'));
    }

    public function downloadOrderInvoice($orderId)
    {
        $order = \App\Models\Order::with(['items.food', 'user', 'address', 'restaurant'])->findOrFail($orderId);
        
        // Ensure user is downloading their own order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $setting = \App\Models\CompanySetting::firstSetting();
        
        // Map order data to the expected $invoice structure
        $invoice = [
            'invoice_number' => ($setting->invoice_prefix ?? 'INV-') . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            'date' => $order->created_at->format($setting->date_format ?? 'd/m/Y'),
            'due_date' => $order->created_at->format($setting->date_format ?? 'd/m/Y'),
            'customer_name' => $order->user->name,
            'customer_email' => $order->user->email,
            'customer_phone' => $order->user->phone ?? 'N/A',
            'customer_address' => $order->address ? "{$order->address->house_no}, {$order->address->street}, {$order->address->city}" : 'N/A',
            'items' => [],
            'subtotal' => $order->subtotal,
            'tax_percentage' => \App\Models\Setting::where('key', 'tax_percentage')->value('value') ?? 0,
            'tax_amount' => $order->tax,
            'discount' => $order->discount + $order->promo_discount,
            'delivery_fee' => $order->delivery_charge,
            'total' => $order->total,
        ];

        foreach ($order->items as $item) {
            $invoice['items'][] = [
                'name' => $item->food ? $item->food->name : 'Food Item',
                'quantity' => $item->qty,
                'price' => $item->price,
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('manage.admin.invoices.template', compact('setting', 'invoice'));
        return $pdf->download('invoice-' . $invoice['invoice_number'] . '.pdf');
    }

    public function myTransactions()
    {
        $transactions = \App\Models\Transaction::with(['order', 'restaurant'])
            ->where('customer_id', auth()->user()->id)
            ->latest()
            ->paginate(10);

        $currencySymbol = \App\Models\Setting::where('key', 'currency_symbol')->value('value') ?? '$';

        return view('manage.front.my-transactions', compact('transactions', 'currencySymbol'));
    }

    public function downloadReceipt($transactionId)
    {
        $transaction = \App\Models\Transaction::with(['order.items.food', 'restaurant'])
            ->where('customer_id', auth()->user()->id)
            ->findOrFail($transactionId);

        $currencySymbol = \App\Models\Setting::where('key', 'currency_symbol')->value('value') ?? '$';
        $companySetting = \App\Models\CompanySetting::first();

        // Use the dompdf wrapper directly from the container to avoid Facade caching issues
        $pdf = app('dompdf.wrapper')->loadView('manage.front.receipt-pdf', compact('transaction', 'currencySymbol', 'companySetting'));
        
        return $pdf->download('Receipt-' . $transaction->transaction_number . '.pdf');
    }

    /**
     * Cancel a customer order. Cancellation is only allowed while the order
     * is still pending (before the restaurant accepts and food prep starts).
     * Online payments get a gateway refund (if already paid) and any recorded
     * financial distribution (incomes) for the order is reversed.
     */
    public function cancelOrder(Request $request, Order $order)
    {
        $order = Order::with('restaurant')->where('user_id', auth()->id())
        ->findOrFail($order->id);

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been paid, so it cannot be cancelled online. Please contact customer support for assistance.',
            ], 422);
        }

        if ($order->status !== Order::STATUS_PENDING) {
            $reason = match ($order->status) {
                Order::STATUS_ACCEPTED => 'Your order has already been accepted and food preparation has started. Cancellation and refund are not available at this stage.',
                Order::STATUS_PREPARING => 'Your order is being prepared. Cancellation is not allowed once preparation has started.',
                Order::STATUS_READY => 'Your order is ready. Cancellation is no longer available.',
                Order::STATUS_ASSIGNED => 'A delivery partner has already been assigned to your order. Cancellation is not allowed.',
                Order::STATUS_PICKED_UP, Order::STATUS_OUT_FOR_DELIVERY => 'Your order is already on its way. Cancellation is not allowed.',
                Order::STATUS_DELIVERED, Order::STATUS_COMPLETED => 'This order has already been delivered and completed.',
                Order::STATUS_CANCELLED => 'This order has already been cancelled.',
                Order::STATUS_REJECTED => 'This order has already been rejected by the restaurant.',
                default => 'This order cannot be cancelled at its current stage.',
            };

            return response()->json(['success' => false, 'message' => $reason], 422);
        }

        $isOnlinePayment = in_array($order->payment_method, ['card', 'wallet'], true);
        $refundedAmount = 0.00;

        // Calculate refund: online payments that were already charged get a
        // gateway refund; Cash on Delivery orders never collect money upfront.
        if ($isOnlinePayment && $order->payment_status === 'paid') {
            $refundedAmount = (float) $order->total;
        }

        // Reverse financial distribution: remove any income rows recorded for
        // this order (commission, tax, platform share, payouts, etc.).
        Income::where('order_id', $order->id)->delete();

        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'payment_status' => $refundedAmount > 0 ? 'refunded' : 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => 'Cancelled by customer.',
        ]);

        AuditLog::log(
            'Order',
            'cancelled',
            null,
            $order->fresh()->toArray(),
            "Order #{$order->id} cancelled by customer. Refund: " . ($refundedAmount > 0 ? number_format($refundedAmount, 2) : 'N/A')
        );

        $message = $refundedAmount > 0
            ? 'Your order has been cancelled successfully. A refund of ' . $this->currencySymbolForMessage() . number_format($refundedAmount, 2) . ' is being processed and will be credited back to your account.'
            : 'Your order has been cancelled successfully.';

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    private function currencySymbolForMessage(): string
    {
        return CompanySetting::firstSetting()->currencySymbol();
    }
    public function orderTracking(Request $request)
    {
        $order = null;

        if ($request->has('order')) {
            $order = Order::with(['address', 'items', 'restaurant', 'deliveryPartner', 'review'])
                ->where('user_id', auth()->id())
                ->find($request->query('order'));
        }

        if (!$order) {
            $order = Order::with(['address', 'items', 'restaurant', 'deliveryPartner', 'review'])
                ->where('user_id', auth()->id())
                ->latest()
                ->first();
        }

        return view('manage.front.orderTracking', compact('order'));
    }

    public function storeReview(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'restaurant_rating' => 'nullable|integer|between:1,5',
            'food_rating' => 'nullable|integer|between:1,5',
            'delivery_rating' => 'nullable|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($data['order_id']);

        if ($order->status !== Order::STATUS_DELIVERED) {
            return back()->with('error', 'You can only review a delivered order.');
        }

        if ($order->review) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        Review::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'restaurant_id' => $order->restaurant_id,
            'delivery_partner_id' => $order->delivery_partner_id,
            'restaurant_rating' => $data['restaurant_rating'] ?? null,
            'food_rating' => $data['food_rating'] ?? null,
            'delivery_rating' => $data['delivery_rating'] ?? null,
            'comment' => $data['comment'] ?? null,
        ]);

        $order->update([
            'status' => Order::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }

    public function updateReview(Request $request, \App\Models\Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $data = $request->validate([
            'restaurant_rating' => 'nullable|integer|between:1,5',
            'food_rating' => 'nullable|integer|between:1,5',
            'delivery_rating' => 'nullable|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'restaurant_rating' => $data['restaurant_rating'] ?? null,
            'food_rating' => $data['food_rating'] ?? null,
            'delivery_rating' => $data['delivery_rating'] ?? null,
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Your feedback has been updated successfully.');
    }

    public function destroyReview(\App\Models\Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $review->delete();

        return back()->with('success', 'Your feedback has been deleted successfully.');
    }
    public function savedAddress()
    {
        $addresses = auth()->user()->addresses()->latest()->get();

        return view('manage.front.savedAddress', compact('addresses'));
    }

    public function addressStore(Request $request)
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'zip' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        auth()->user()->addresses()->create($data);

        return back()->with('success', 'Address added successfully.');
    }

    public function addressUpdate(Request $request, $id)
    {
        $address = auth()->user()->addresses()->findOrFail($id);

        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'zip' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $address->update($data);

        return back()->with('success', 'Address updated successfully.');
    }

    public function addressDestroy($id)
    {
        $address = auth()->user()->addresses()->findOrFail($id);
        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }
    /**
     * Customer Front: Digital Wallet & Top-up Dashboard
     */
    public function wallet(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->getOrCreateWallet();

        $query = $user->walletTransactions()->latest();

        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        $walletTransactions = $query->paginate(10)->withQueryString();

        $stats = [
            'balance' => (float) $wallet->balance,
            'total_added' => (float) $user->walletTransactions()->where('type', 'credit')->sum('amount'),
            'total_spent' => (float) $user->walletTransactions()->where('type', 'debit')->sum('amount'),
            'total_transactions' => (int) $user->walletTransactions()->count(),
        ];

        $rzpGateway = PaymentGateway::getGateway('razorpay');
        $razorpayKey = $rzpGateway && !empty($rzpGateway->key_id) ? $rzpGateway->key_id : (config('services.razorpay.key') ?? 'rzp_test_TWIAoYszpcVthe');
        $companySetting = CompanySetting::first();
        $companyName = $companySetting?->company_name ?? 'FoodExpress';
        $companyLogo = $companySetting?->logo ?? 'front/assets/images/logo/logo.png';

        return view('manage.front.wallet', compact('wallet', 'walletTransactions', 'stats', 'razorpayKey', 'companyName', 'companyLogo'));
    }

    /**
     * Customer Front: Process Wallet Top-up and credit wallet immediately
     */
    public function walletTopupSuccess(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:500000'],
            'payment_method' => ['required', 'string'],
            'payment_id' => ['nullable', 'string'],
        ]);

        $user = auth()->user();
        $amount = round((float) $data['amount'], 2);
        $gateway = $data['payment_method'] ?: 'razorpay';
        $paymentId = $data['payment_id'] ?: ('WTP_' . strtoupper(uniqid()));

        // 1. Create Transaction Record
        $txnNumber = Transaction::generateTransactionNumber('WTP');
        $transaction = Transaction::create([
            'transaction_number' => $txnNumber,
            'type' => 'wallet_topup',
            'customer_id' => $user->id,
            'customer_name' => $user->name,
            'phone' => $user->phone,
            'total_amount' => $amount,
            'admin_share_percent' => 0,
            'admin_amount' => 0,
            'restaurant_share_percent' => 0,
            'restaurant_amount' => 0,
            'payment_method' => $gateway,
            'payment_gateway' => $gateway,
            'payment_id' => $paymentId,
            'payment_status' => Transaction::PAYMENT_STATUS_PAID,
        ]);

        // 2. Credit Wallet Balance
        $gatewayName = ucfirst(str_replace('_', ' ', $gateway));
        $walletTxn = $user->getOrCreateWallet()->credit(
            $amount,
            'wallet_topup',
            $transaction->id,
            "Wallet Top-Up via {$gatewayName}"
        );

        // 3. Log Audit
        AuditLog::log(
            'Wallet Top-Up',
            'completed',
            null,
            [
                'user_id' => $user->id,
                'amount' => $amount,
                'gateway' => $gateway,
                'new_balance' => $user->fresh()->wallet_balance,
            ],
            "User #{$user->id} ({$user->name}) added ₹{$amount} to wallet via {$gatewayName}."
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '₹' . number_format($amount, 2) . ' successfully added to your wallet!',
                'new_balance' => number_format($user->fresh()->wallet_balance, 2),
                'transaction_number' => $walletTxn->transaction_number,
            ]);
        }

        return redirect()->route('wallet')->with('success', '₹' . number_format($amount, 2) . ' successfully added to your wallet!');
    }

    public function savedCard()
    {
        $cards = auth()->user()->cards()->latest()->get();

        return view('manage.front.savedCard', compact('cards'));
    }

    public function cardStore(Request $request)
    {
        $data = $request->validate([
            'holder_name' => ['nullable', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:255'],
            'exp_date' => ['nullable', 'date'],
            'cvv' => ['nullable', 'string', 'max:10'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        auth()->user()->cards()->create($data);

        return back()->with('success', 'Card added successfully.');
    }

    public function cardUpdate(Request $request, $id)
    {
        $card = auth()->user()->cards()->findOrFail($id);

        $data = $request->validate([
            'holder_name' => ['nullable', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:255'],
            'exp_date' => ['nullable', 'date'],
            'cvv' => ['nullable', 'string', 'max:10'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $card->update($data);

        return back()->with('success', 'Card updated successfully.');
    }

    public function cardDestroy($id)
    {
        $card = auth()->user()->cards()->findOrFail($id);
        $card->delete();

        return back()->with('success', 'Card deleted successfully.');
    }
    public function setting()
    {
        return view('manage.front.setting');
    }
    public function checkout()
    {
        return view('manage.front.checkout');
    }
    public function address()
    {
        return view('manage.front.address');
    }

    public function addressApi($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);

        return response()->json($address);
    }
    public function restaurantApi($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        return response()->json([
            'id' => $restaurant->id,
            'restaurant_name' => $restaurant->restaurant_name,
            'latitude' => (float) $restaurant->latitude,
            'longitude' => (float) $restaurant->longitude,
            'delivery_radius' => (float) $restaurant->delivery_radius,
            'delivery_charge_per_km' => (float) $restaurant->delivery_charge_per_km,
        ]);
    }

    /**
     * Great-circle distance (km) between two coordinates.
     */
    private function distanceBetween(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371.0;
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
    public function payment()
    {
        $activeGateways = PaymentGateway::where('is_active', true)->orderBy('sort_order')->get();
        $rzpGateway = PaymentGateway::getGateway('razorpay');
        $razorpayKey = $rzpGateway && !empty($rzpGateway->key_id) ? $rzpGateway->key_id : (config('services.razorpay.key') ?? 'rzp_test_TWIAoYszpcVthe');
        $companySetting = CompanySetting::first();
        $companyName = $companySetting?->company_name ?? 'FoodExpress';
        $companyLogo = $companySetting?->logo ?? 'front/assets/images/logo/logo.png';
        $userCards = auth()->check() ? auth()->user()->cards()->latest()->get() : collect();

        return view('manage.front.payment', compact('activeGateways', 'razorpayKey', 'companyName', 'companyLogo', 'userCards'));
    }
    public function confirmOrder(Request $request)
    {
        $order = null;

        if ($request->has('order')) {
            $order = Order::with(['address', 'items'])
                ->where('user_id', auth()->id())
                ->find($request->query('order'));
        }

        return view('manage.front.confirmOrder', compact('order'));
    }

    /**
     * Validate a promo code from the payment page and return the discount amount.
     */
    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = PromoCode::where('code', strtoupper(trim($request->code)))->first();

        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Invalid promo code.'], 404);
        }

        if (!$code->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This promo code is not valid or has already been used.',
            ], 422);
        }

        $subtotal = (float) $request->subtotal;

        if ($code->minimum_order_amount > 0 && $subtotal < $code->minimum_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount is ' . $this->currencySymbolForMessage() . number_format($code->minimum_order_amount, 2) . '.',
            ], 422);
        }

        $user = $request->user();
        $usedByUser = PromoCode::where('campaign_name', $code->campaign_name)
            ->where('usage_status', 'used')
            ->where('assigned_user_id', $user->id)
            ->count();

        if ($usedByUser >= $code->per_user_limit) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used the maximum allowed codes from this campaign.',
            ], 422);
        }

        $discount = round($code->computeDiscount($subtotal), 2);

        return response()->json([
            'success' => true,
            'code' => $code->code,
            'promo_code_id' => $code->id,
            'discount' => $discount,
            'message' => 'Promo applied: ' . $this->currencySymbolForMessage() . number_format($discount, 2) . ' off.',
        ]);
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'delivery_option' => 'required|string|in:standard,express',
            'payment_method' => 'required|string|in:cash_on_delivery,card,wallet,razorpay,phonepe,paytm,payu,payumoney,paypal',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1',
            'promo_code' => 'nullable|string',
            'payment_id' => 'nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $user = $request->user();

        $address = Address::where('user_id', $user->id)->findOrFail($data['address_id']);

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += (float) $item['price'] * (int) $item['qty'];
        }

        $discount = 0;
        $firstFoodId = $data['items'][0]['id'] ?? null;
        $firstRestaurant = $firstFoodId ? Food::find($firstFoodId)?->restaurant : null;
        $rate = (float) ($firstRestaurant?->delivery_charge_per_km ?: 20);

        $deliveryCharge = $rate;
        if ($firstRestaurant && $firstRestaurant->latitude && $firstRestaurant->longitude
            && $address->latitude && $address->longitude) {
            $distance = $this->distanceBetween(
                (float) $firstRestaurant->latitude,
                (float) $firstRestaurant->longitude,
                (float) $address->latitude,
                (float) $address->longitude,
            );

            $radius = (float) $firstRestaurant->delivery_radius;

           // dd($radius, $distance);
            if ($radius > 0 && $distance > $radius) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery is not available for this address (outside the delivery radius).',
                ], 422);
            }

            $deliveryCharge = round($distance * $rate, 2);
        }

        $taxPercent = CompanySetting::firstSetting()->taxGstPercentage();
        $tax = round($subtotal * $taxPercent / 100, 2);

        // Promo code discount
        $promoDiscount = 0;
        $promoCodeModel = null;
        if (!empty($data['promo_code'])) {
            $promo = PromoCode::where('code', strtoupper(trim($data['promo_code'])))->first();
            if ($promo && $promo->isValid()
                && ($promo->minimum_order_amount <= 0 || $subtotal >= $promo->minimum_order_amount)) {
                $usedByUser = PromoCode::where('campaign_name', $promo->campaign_name)
                    ->where('usage_status', 'used')
                    ->where('assigned_user_id', $user->id)
                    ->count();
                if ($usedByUser < $promo->per_user_limit) {
                    $promoDiscount = round($promo->computeDiscount($subtotal), 2);
                    $promoCodeModel = $promo;
                }
            }
        }

        $total = $subtotal - $discount - $promoDiscount + $deliveryCharge + $tax;

        $isCod = ($data['payment_method'] === 'cash_on_delivery');
        $isWalletPayment = ($data['payment_method'] === 'wallet');
        $isPrepaid = !$isCod;

        // If paying via wallet, verify sufficient balance
        if ($isWalletPayment) {
            $userWallet = $user->getOrCreateWallet();
            if ((float) $userWallet->balance < (float) $total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. Your balance is ₹' . number_format($userWallet->balance, 2) . ', but order total is ₹' . number_format($total, 2) . '.',
                ], 422);
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'delivery_option' => $data['delivery_option'],
            'delivery_charge' => $deliveryCharge,
            'payment_method' => $data['payment_method'],
            'payment_status' => $isPrepaid ? 'paid' : 'pending',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'promo_code_id' => $promoCodeModel?->id,
            'promo_code' => $promoCodeModel?->code,
            'promo_discount' => $promoDiscount,
            'status' => 'pending',
        ]);

        // Debit customer wallet if paying with wallet
        if ($isWalletPayment) {
            $user->getOrCreateWallet()->debit(
                $total,
                'order_payment',
                $order->id,
                "Payment for Food Order #{$order->id}"
            );
        }

        if ($promoCodeModel) {
            $promoCodeModel->markUsed($user->id);
        }

        $firstRestaurantId = null;
        foreach ($data['items'] as $item) {
            $food = Food::find($item['id']);
            $restaurantId = $food ? $food->restaurant_id : null;

            if ($firstRestaurantId === null) {
                $firstRestaurantId = $restaurantId;
            }

            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $food ? $food->id : null,
                'restaurant_id' => $restaurantId,
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'subtotal' => (float) $item['price'] * (int) $item['qty'],
            ]);
        }

        $order->update(['restaurant_id' => $firstRestaurantId]);

        // If prepaid (Gateways, Card, Wallet), distribute income immediately (COD will be settled upon delivery)
        if ($isPrepaid) {
            $paymentId = $request->input('payment_id') ?: $request->input('razorpay_payment_id');
            $orderRefId = $request->input('razorpay_order_id');
            $signature = $request->input('razorpay_signature');
            $this->distributeOrderIncome($order, $data['payment_method'], $paymentId, $orderRefId, $signature);
        }

        // Notify restaurant owner
        $restaurant = \App\Models\Restaurant::find($firstRestaurantId);
        if ($restaurant && $restaurant->user) {
            $restaurant->user->notify(new \App\Notifications\NewOrderNotification($order));
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'redirect' => route('confirmOrder', ['order' => $order->id]),
        ]);
    }

    /**
     * Distribute income for a prepaid/paid food order (Gateways, Card, Wallet).
     * Follows the exact logic from Admin Settlements (CodSettlementController).
     */
    protected function distributeOrderIncome(Order $order, string $paymentMethod, ?string $paymentId = null, ?string $orderRefId = null, ?string $signature = null): void
    {
        $setting = CompanySetting::firstSetting();
        $restaurant = $order->restaurant ?: Restaurant::find($order->restaurant_id);

        $foodTotal = round((float) $order->subtotal - (float) $order->discount - (float) ($order->promo_discount ?? 0), 2);
        if ($foodTotal < 0) {
            $foodTotal = 0;
        }

        $deliveryCharge = (float) $order->delivery_charge;
        $tax = (float) $order->tax;

        $commissionPct = (float) ($restaurant?->commission_percentage ?: 10);
        $partnerSharePct = (float) ($setting->delivery_partner_share ?: 85);
        $platformSharePct = (float) ($setting->platform_share ?: 15);

        $commission = round($foodTotal * $commissionPct / 100, 2);
        $restaurantPayout = round($foodTotal - $commission, 2);
        $deliveryPartnerPayout = round($deliveryCharge * $partnerSharePct / 100, 2);
        $platformDeliveryShare = round($deliveryCharge * $platformSharePct / 100, 2);
        $platformTotalPayout = round($commission + $tax + $platformDeliveryShare, 2);

        $taxPct = (float) ($setting?->taxGstPercentage() ?: 5);

        // 1. Log in Transactions table
        $txnNumber = Transaction::generateTransactionNumber('ORD');
        Transaction::create([
            'transaction_number' => $txnNumber,
            'type' => Transaction::TYPE_ORDER,
            'order_id' => $order->id,
            'restaurant_id' => $restaurant?->id,
            'customer_id' => $order->user_id,
            'customer_name' => $order->user?->name,
            'phone' => $order->user?->phone,
            'total_amount' => $order->total,
            'admin_share_percent' => $commissionPct,
            'admin_amount' => $platformTotalPayout,
            'restaurant_share_percent' => (100 - $commissionPct),
            'restaurant_amount' => $restaurantPayout,
            'payment_method' => $paymentMethod,
            'payment_gateway' => $paymentMethod === 'wallet' ? 'wallet' : ($paymentMethod === 'card' ? 'card' : $paymentMethod),
            'payment_id' => $paymentId ?: ('ORD_' . strtoupper(uniqid())),
            'order_reference_id' => $orderRefId,
            'signature' => $signature,
            'payment_status' => Transaction::PAYMENT_STATUS_PAID,
            'status' => Transaction::STATUS_SUCCESS,
            'note' => "Food Order #{$order->id} paid via " . strtoupper($paymentMethod),
            'meta_data' => [
                'food_total' => $foodTotal,
                'delivery_charge' => $deliveryCharge,
                'tax' => $tax,
                'commission_pct' => $commissionPct,
                'commission' => $commission,
                'partner_share_pct' => $partnerSharePct,
                'platform_share_pct' => $platformSharePct,
                'restaurant_payout' => $restaurantPayout,
                'delivery_partner_payout' => $deliveryPartnerPayout,
                'platform_payout' => $platformTotalPayout,
            ],
            'paid_at' => now(),
        ]);

        // 2. Record Incomes table ledger entries
        Income::where('order_id', $order->id)->delete();

        $entries = [
            [
                'income_type' => Income::TYPE_RESTAURANT_COMMISSION,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'restaurant_id' => $restaurant?->id,
                'base_amount' => $foodTotal,
                'percentage' => $commissionPct,
                'amount' => $commission,
            ],
            [
                'income_type' => Income::TYPE_TAX,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'restaurant_id' => $restaurant?->id,
                'base_amount' => $foodTotal,
                'percentage' => $taxPct,
                'amount' => $tax,
            ],
            [
                'income_type' => Income::TYPE_PLATFORM_SHARE,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'restaurant_id' => $restaurant?->id,
                'base_amount' => $deliveryCharge,
                'percentage' => $platformSharePct,
                'amount' => $platformDeliveryShare,
            ],
            [
                'income_type' => Income::TYPE_RESTAURANT_PAYOUT,
                'recipient_type' => Income::RECIPIENT_RESTAURANT,
                'recipient_id' => $restaurant?->user_id,
                'user_id' => $restaurant?->user_id,
                'restaurant_id' => $restaurant?->id,
                'base_amount' => $foodTotal,
                'percentage' => (100 - $commissionPct),
                'amount' => $restaurantPayout,
            ],
            [
                'income_type' => Income::TYPE_DELIVERY_PARTNER_PAYOUT,
                'recipient_type' => Income::RECIPIENT_DELIVERY_PARTNER,
                'recipient_id' => $order->delivery_partner_id,
                'user_id' => $order->delivery_partner_id,
                'restaurant_id' => $restaurant?->id,
                'base_amount' => $deliveryCharge,
                'percentage' => $partnerSharePct,
                'amount' => $deliveryPartnerPayout,
            ],
        ];

        foreach ($entries as $entry) {
            Income::create($entry + [
                'order_id' => $order->id,
                'paid_at' => now(),
            ]);
        }

        // 3. Credit Restaurant Owner Wallet for food payout share
        if ($restaurant && $restaurant->user_id && $restaurantPayout > 0) {
            $restaurantOwner = User::find($restaurant->user_id);
            if ($restaurantOwner) {
                $restaurantOwner->getOrCreateWallet()->credit(
                    $restaurantPayout,
                    'order_restaurant_payout',
                    $order->id,
                    "Prepaid food order payout for Order #{$order->id} at {$restaurant->restaurant_name}"
                );
            }
        }
    }

    public function collectionsList()
    {
        $collections = NightlifeBanner::withCount('restaurants')
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('manage.front.collections', compact('collections'));
    }

    public function collectionDetails(Request $request)
    {
        // Check for both lightlife (typo in prompt) and nightlife just in case
        $slug = $request->query('nightlife') ?? $request->query('lightlife');
        
        if (!$slug) {
            return redirect()->route('collections.index');
        }

        $collection = NightlifeBanner::withCount('restaurants')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $restaurants = $collection->restaurants()->with(['city'])->paginate(12);

        return view('manage.front.collectionDetails', compact('collection', 'restaurants'));
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
protected $fillable = [
        'user_id',
        'brand_id',
        'nightlife_banner_id',
        'restaurant_name',
        'restaurant_type',
        'restaurant_slug',
        'owner_name',
        'email',
        'mobile',
        'logo',
        'banner',
        'description',
        'gst_number',
        'fssai_number',
        'pan_number',
        'bank_name',
        'account_number',
        'ifsc_code',
        'qr_code',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'postal_code',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'minimum_order_amount',
        'delivery_radius',
        'estimated_delivery_time',
        'commission_percentage',
        'dining_commission_percentage',
        'slot_duration_minutes',
        'advance_booking_days',
        'total_tables_count',
        'is_pure_veg',
        'pet_friendly',
        'outdoor_seating',
        'serves_alcohol',
        'credit_card',
        'buffet',
        'happy_hours',
        'pubs_bars',
        'fine_dining',
        'wifi',
        'cafes',
        'hygiene_rated',
        'online_bookings',
        'status',
        'approval_status',
        'admin_remarks',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_pure_veg' => 'boolean',
            'pet_friendly' => 'boolean',
            'outdoor_seating' => 'boolean',
            'serves_alcohol' => 'boolean',
            'credit_card' => 'boolean',
            'buffet' => 'boolean',
            'happy_hours' => 'boolean',
            'pubs_bars' => 'boolean',
            'fine_dining' => 'boolean',
            'wifi' => 'boolean',
            'cafes' => 'boolean',
            'hygiene_rated' => 'boolean',
            'online_bookings' => 'boolean',
            'minimum_order_amount' => 'decimal:2',
            'delivery_radius' => 'decimal:2',
            'delivery_charge_per_km' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
            'slot_duration_minutes' => 'integer',
            'advance_booking_days' => 'integer',
            'total_tables_count' => 'integer',
            'opening_time' => 'datetime:H:i',
            'closing_time' => 'datetime:H:i',
            'pan_number' => 'string',
            'bank_name' => 'string',
            'account_number' => 'string',
            'ifsc_code' => 'string',
            'qr_code' => 'string',
        ];
    }

    /**
     * Scope: restaurants that are approved and live/visible to customers.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')->where('status', 'active');
    }

    /**
     * Relationship to the assigned User / Restaurant Owner account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship to Brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Relationship to Nightlife Banner.
     */
    public function nightlifeBanner(): BelongsTo
    {
        return $this->belongsTo(NightlifeBanner::class, 'nightlife_banner_id');
    }

    /**
     * Relationship to Food Categories.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(FoodCategory::class, 'restaurant_id');
    }

    /**
     * Relationship to Cuisines (Many-to-Many).
     */
    public function cuisines(): BelongsToMany
    {
        return $this->belongsToMany(Cuisine::class, 'restaurant_cuisine')
                    ->withTimestamps();
    }

    /**
     * Relationship to Food Items.
     */
    public function foods(): HasMany
    {
        return $this->hasMany(Food::class, 'restaurant_id');
    }

    /**
     * Relationship to Restaurant Tables.
     */
    public function tables(): HasMany
    {
        return $this->hasMany(RestaurantTable::class, 'restaurant_id');
    }

    /**
     * Relationship to Table Bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'restaurant_id');
    }

    /**
     * Relationship to Country.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Relationship to State.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Relationship to City.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Relationship to Orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'restaurant_id');
    }

    public function diningOffers(): HasMany
    {
        return $this->hasMany(DiningOffer::class, 'restaurant_id');
    }

    /**
     * Relationship to Restaurant Blogs.
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(RestaurantBlog::class, 'restaurant_id');
    }

    /**
     * Relationship to Customer Reviews.
     */
    public function reviews(): HasMany

    {
        return $this->hasMany(Review::class, 'restaurant_id');
    }

    /**
     * Relationship to the Admin creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Relationship to the Admin updater.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Generate automatic time slots from opening_time to closing_time.
     */
    public function generateTimeSlots(?string $date = null): array
    {
        $opening = $this->getRawOriginal('opening_time') ?? '11:00:00';
        $closing = $this->getRawOriginal('closing_time') ?? '23:00:00';
        $duration = (int) ($this->slot_duration_minutes ?: 60);

        if ($duration < 15) {
            $duration = 60;
        }

        $openTime = \Carbon\Carbon::parse($opening);
        $closeTime = \Carbon\Carbon::parse($closing);

        if ($closeTime->lessThanOrEqualTo($openTime)) {
            $closeTime->addDay();
        }

        $slots = [];
        $current = $openTime->copy();

        while ($current->lt($closeTime)) {
            $slotStart = $current->format('H:i');
            $slotDisplay = $current->format('g:i A');
            $next = $current->copy()->addMinutes($duration);
            $slotEndDisplay = $next->format('g:i A');
            $hour = (int) $current->format('H');
            $mealType = ($hour < 16) ? 'lunch' : 'dinner';

            $slots[] = [
                'time' => $slotStart,
                'display' => $slotDisplay,
                'label' => $slotDisplay . ' - ' . $slotEndDisplay,
                'start_time' => $slotStart,
                'end_time' => $next->format('H:i'),
                'meal' => $mealType,
            ];

            $current->addMinutes($duration);
        }


        return $slots;
    }

    /**
     * Calculate table & seat availability for a specific date and time slot.
     */
    public function getSlotAvailability(string $date, string $timeSlot, int $guests = 1): array
    {
        $allTables = $this->tables()->get();
        $totalTables = $allTables->count();
        $availableConfigTables = $allTables->where('status', RestaurantTable::STATUS_AVAILABLE);

        // Normalize time slot
        $normalizedTime = strlen($timeSlot) === 5 ? $timeSlot . ':00' : $timeSlot;

        // Active bookings at this date & time
        $activeBookings = Booking::where('restaurant_id', $this->id)
            ->where('book_date', $date)
            ->where(function ($q) use ($timeSlot, $normalizedTime) {
                $q->where('book_time', $timeSlot)
                  ->orWhere('book_time', $normalizedTime)
                  ->orWhere('book_time', 'like', substr($timeSlot, 0, 5) . '%');
            })
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_ACCEPTED])
            ->get();

        $bookedTableIds = $activeBookings->pluck('restaurant_table_id')->filter()->toArray();
        $bookedCount = count($activeBookings);

        // Tables available right now
        $freeTables = $availableConfigTables->reject(function ($table) use ($bookedTableIds) {
            return in_array($table->id, $bookedTableIds);
        });

        // Filter for capacity if requested
        $suitableTables = $freeTables->filter(function ($table) use ($guests) {
            return $table->capacity >= $guests;
        });

        $freeCount = $freeTables->count();
        $isAvailable = $totalTables > 0 ? ($freeCount > 0) : true;

        $status = 'available';
        if ($totalTables > 0 && $freeCount === 0) {
            $status = 'full';
        } elseif ($totalTables > 0 && $freeCount <= 2) {
            $status = 'limited';
        }

        return [
            'date' => $date,
            'time' => $timeSlot,
            'total_tables' => $totalTables,
            'booked_count' => $bookedCount,
            'available_count' => $freeCount,
            'suitable_count' => $suitableTables->count(),
            'is_available' => $isAvailable,
            'status' => $status,
            'suitable_tables' => $suitableTables->values(),
            'free_tables' => $freeTables->values(),
        ];
    }
}


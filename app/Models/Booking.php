<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'dining_offer_id',
        'restaurant_table_id',
        'customer_id',
        'customer_name',
        'phone',
        'book_date',
        'book_time',
        'guests',
        'cover_charge',
        'payment_method',
        'payment_status',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'food_bill',
        'bill_items',
        'status',
        'bill_status',
        'bill_paid_at',
        'note',
    ];

    protected $casts = [
        'book_date' => 'date',
        'book_time' => 'datetime',
        'guests' => 'integer',
        'cover_charge' => 'decimal:2',
        'food_bill' => 'decimal:2',
        'bill_items' => 'array',
        'bill_paid_at' => 'datetime',
    ];


    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const BILL_STATUS_PENDING = 'pending';

    public const BILL_STATUS_PAID = 'paid';

    public const TAX_PERCENTAGE = 5;

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }


    public function diningOffer(): BelongsTo
    {
        return $this->belongsTo(DiningOffer::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class, 'booking_id');
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class, 'booking_id')->latestOfMany();
    }

    public function incomes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Income::class, 'booking_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isBillPaid(): bool
    {
        return $this->bill_status === self::BILL_STATUS_PAID;
    }

    /**
     * Compute the dining bill breakdown for a confirmed booking.
     * Subtotal is derived from the itemised bill_items (POS style); the discount
     * comes from the selected dining offer (percentage/fixed, with min bill &
     * max discount limits). A flat cover charge (if set on the offer) is added,
     * then tax is applied on the discounted amount plus cover charge.
     */
    public function billBreakdown(): array
    {
        $subtotal = $this->subtotal();
        $discount = 0;
        $coverCharge = 0;
        $offer = $this->diningOffer;

        if ($offer && $subtotal > 0) {
            $eligible = ! $offer->min_bill_amount || $subtotal >= (float) $offer->min_bill_amount;

            if ($eligible) {
                if ($offer->discount_type === 'percentage') {
                    $discount = $subtotal * ((float) $offer->discount_value / 100);
                    if ($offer->max_discount_amount) {
                        $discount = min($discount, (float) $offer->max_discount_amount);
                    }
                } else {
                    $discount = (float) $offer->discount_value;
                }
                $discount = min($discount, $subtotal);
            }
        }

        if ($offer && $offer->cover_charge) {
            $coverCharge = (float) $offer->cover_charge;
        }

        $discounted = $subtotal - $discount;
        $taxable = $discounted + $coverCharge;
        $tax = $taxable * (self::TAX_PERCENTAGE / 100);
        $payable = $taxable + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'cover_charge' => round($coverCharge, 2),
            'tax' => round($tax, 2),
            'payable' => round($payable, 2),
            'items' => $this->bill_items ?? [],
            'offer' => $offer ? $offer->title : null,
            'discount_label' => $offer
                ? ($offer->discount_type === 'percentage'
                    ? $offer->discount_value.'% OFF'
                    : 'Flat '.$offer->discount_value.' OFF')
                : null,
        ];
    }

    /**
     * Subtotal from itemised bill rows: sum(price * qty).
     * Falls back to the stored food_bill when no items exist (legacy).
     */
    public function subtotal(): float
    {
        $items = $this->bill_items ?? [];
        if (empty($items)) {
            return (float) ($this->food_bill ?? 0);
        }

        $total = 0;
        foreach ($items as $item) {
            $total += ((float) ($item['price'] ?? 0)) * ((int) ($item['qty'] ?? 0));
        }

        return $total;
    }
}

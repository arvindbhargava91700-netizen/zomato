<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    public const TYPE_RESTAURANT_COMMISSION = 'restaurant_commission';
    public const TYPE_TAX = 'tax_gst';
    public const TYPE_PLATFORM_SHARE = 'platform_share';
    public const TYPE_RESTAURANT_PAYOUT = 'restaurant_payout';
    public const TYPE_COVER_CHARGE_PAYMENT = 'cover_charge_payment';
    public const TYPE_DELIVERY_PARTNER_PAYOUT = 'delivery_partner_payout';

    public const RECIPIENT_PLATFORM = 'platform';
    public const RECIPIENT_RESTAURANT = 'restaurant';
    public const RECIPIENT_DELIVERY_PARTNER = 'delivery_partner';

    public const TYPE_LABELS = [
        self::TYPE_RESTAURANT_COMMISSION => 'Restaurant Commission',
        self::TYPE_TAX => 'Tax / GST',
        self::TYPE_PLATFORM_SHARE => 'Platform Share',
        self::TYPE_RESTAURANT_PAYOUT => 'Restaurant Payout',
        self::TYPE_COVER_CHARGE_PAYMENT => 'Cover Charge Payment',
        self::TYPE_DELIVERY_PARTNER_PAYOUT => 'Delivery Partner Payout',
    ];

    public const RECIPIENT_LABELS = [
        self::RECIPIENT_PLATFORM => 'Platform',
        self::RECIPIENT_RESTAURANT => 'Restaurant',
        self::RECIPIENT_DELIVERY_PARTNER => 'Delivery Partner',
    ];

    protected $fillable = [
        'order_id',
        'booking_id',
        'restaurant_id',
        'income_type',
        'recipient_type',
        'recipient_id',
        'user_id',
        'base_amount',
        'percentage',
        'amount',
        'paid_at',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function typeLabel(string $type): string
    {
        return self::TYPE_LABELS[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    public static function recipientLabel(string $type): string
    {
        return self::RECIPIENT_LABELS[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }
}
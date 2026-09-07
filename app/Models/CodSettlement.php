<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodSettlement extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'order_id',
        'delivery_partner_id',
        'amount',
        'transaction_id',
        'screenshot_path',
        'status',
        'remark',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'restaurant_payout',
        'delivery_partner_payout',
        'platform_payout',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'restaurant_payout' => 'decimal:2',
        'delivery_partner_payout' => 'decimal:2',
        'platform_payout' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getScreenshotUrlAttribute(): ?string
    {
        return $this->screenshot_path ? asset($this->screenshot_path) : null;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Compute the payout distribution for this settlement's order:
     *  - Restaurant: food total (subtotal - discount) minus restaurant commission
     *  - Delivery Partner: delivery charge x delivery_partner_share
     *  - Platform/Admin: delivery charge x platform_share + tax + restaurant commission
     * Always balances back to the order total.
     */
    public function payoutBreakdown(): array
    {
        $order = $this->order;
        $setting = \App\Models\CompanySetting::firstSetting();

        $foodTotal = round((float) $order->subtotal - (float) $order->discount, 2);
        $deliveryCharge = (float) $order->delivery_charge;
        $tax = (float) $order->tax;

        $commissionPct = (float) ($order->restaurant?->commission_percentage ?: 10);
        $partnerSharePct = (float) ($setting->delivery_partner_share ?: 85);
        $platformSharePct = (float) ($setting->platform_share ?: 15);

        $commission = round($foodTotal * $commissionPct / 100, 2);

        return [
            'food_total' => $foodTotal,
            'delivery_charge' => $deliveryCharge,
            'tax' => $tax,
            'commission_pct' => $commissionPct,
            'commission' => $commission,
            'partner_share_pct' => $partnerSharePct,
            'platform_share_pct' => $platformSharePct,
            'restaurant_payout' => round($foodTotal - $commission, 2),
            'delivery_partner_payout' => round($deliveryCharge * $partnerSharePct / 100, 2),
            'platform_payout' => round($deliveryCharge * $platformSharePct / 100 + $tax + $commission, 2),
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAID => 'Paid',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst($status),
        };
    }

    public static function statusBadge(string $status): string
    {
        $color = match ($status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PAID => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary',
        };

        return "badge bg-soft-{$color} text-{$color}";
    }
}
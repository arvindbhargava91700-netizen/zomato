<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCode extends Model
{
    protected $fillable = [
        'campaign_name',
        'code',
        'code_type',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'per_user_limit',
        'assigned_user_id',
        'usage_status',
        'used_at',
        'valid_from',
        'valid_until',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'used_at' => 'datetime',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->usage_status === 'used') {
            return false;
        }

        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    public function computeDiscount(float $amount): float
    {
        if ($amount <= 0) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = ($amount * $this->discount_value) / 100;
            if ($this->maximum_discount_amount) {
                $discount = min($discount, (float) $this->maximum_discount_amount);
            }
            return $discount;
        }

        return min((float) $this->discount_value, $amount);
    }

    public function markUsed(int $userId): void
    {
        $this->assigned_user_id = $userId;
        $this->usage_status = 'used';
        $this->used_at = now();
        $this->save();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'title',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'valid_from',
        'valid_until',
        'banner',
        'status',
        'approval_status',
        'admin_remarks',
        'approved_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? asset($this->banner) : null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Offers that are active, admin-approved and within their validity window.
     * Use this scope to surface offers on the public "Today's Deal" section.
     */
    public function scopeLive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->active()
            ->approved()
            ->where(function ($q) use ($today) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $today);
            });
    }
}

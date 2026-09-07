<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiningOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'title',
        'coupon_code',
        'discount_type',
        'discount_value',
        'cover_charge',
        'min_bill_amount',
        'max_discount_amount',
        'start_date',
        'end_date',
        'status',
        'approval_status',
        'admin_remarks',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'cover_charge' => 'decimal:2',
        'min_bill_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Only offers that are active and within their date range.
     */
    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Offers that are active, admin-approved and within their validity window.
     */
    public function scopeLive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->active()
            ->approved()
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });
    }
}

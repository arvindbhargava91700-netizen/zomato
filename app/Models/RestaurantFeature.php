<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantFeature extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restaurant_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Scope a query to only include active features.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Relationship to Admin creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Relationship to Admin updater.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}

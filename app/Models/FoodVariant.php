<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodVariant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'food_variants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'restaurant_id',
        'food_id',
        'variant_name',
        'sku',
        'price',
        'sale_price',
        'cost_price',
        'weight',
        'weight_unit',
        'serving_size',
        'preparation_time',
        'status',
        'sort_order',
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
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'preparation_time' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Relationship to Restaurant.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    /**
     * Relationship to Food item.
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'food_id');
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

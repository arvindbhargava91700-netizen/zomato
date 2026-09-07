<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'restaurant_id',
        'food_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'sku',
        'food_type',
        'is_veg',
        'is_featured',
        'is_recommended',
        'is_spicy',
        'preparation_time',
        'base_price',
        'discount_price',
        'tax_percentage',
        'image',
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
            'is_veg' => 'boolean',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'is_spicy' => 'boolean',
            'preparation_time' => 'integer',
            'base_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
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
     * Relationship to Food Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }

    /**
     * Relationship to Cuisines (Many-to-Many).
     */
    public function cuisines(): BelongsToMany
    {
        return $this->belongsToMany(Cuisine::class, 'food_cuisine')
                    ->withTimestamps();
    }

    /**
     * Relationship to Food Variants.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(FoodVariant::class, 'food_id');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'restaurant_id',
        'delivery_partner_id',
        'restaurant_rating',
        'food_rating',
        'delivery_rating',
        'comment',
    ];

    protected $casts = [
        'restaurant_rating' => 'integer',
        'food_rating' => 'integer',
        'delivery_rating' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }
}

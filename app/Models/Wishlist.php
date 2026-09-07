<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'food_id',
        'restaurant_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'food_id' => 'integer',
        'restaurant_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
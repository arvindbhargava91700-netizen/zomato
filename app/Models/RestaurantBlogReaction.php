<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantBlogReaction extends Model
{
    use HasFactory;

    protected $table = 'restaurant_blog_reactions';

    protected $fillable = [
        'restaurant_blog_id',
        'user_id',
        'ip_address',
        'session_id',
        'reaction_type',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(RestaurantBlog::class, 'restaurant_blog_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

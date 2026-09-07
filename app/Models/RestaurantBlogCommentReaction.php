<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantBlogCommentReaction extends Model
{
    use HasFactory;

    protected $table = 'restaurant_blog_comment_reactions';

    protected $fillable = [
        'comment_id',
        'user_id',
        'ip_address',
        'session_id',
        'reaction_type',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(RestaurantBlogComment::class, 'comment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

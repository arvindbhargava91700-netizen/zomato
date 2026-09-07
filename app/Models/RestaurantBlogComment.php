<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantBlogComment extends Model
{
    use HasFactory;

    protected $table = 'restaurant_blog_comments';

    protected $fillable = [
        'restaurant_blog_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'likes_count',
        'dislikes_count',
        'status',
        'session_id',
        'ip_address',
    ];

    protected $casts = [
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(RestaurantBlog::class, 'restaurant_blog_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(RestaurantBlogComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(RestaurantBlogComment::class, 'parent_id')
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(RestaurantBlogCommentReaction::class, 'comment_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if the current viewer is the author of this comment.
     */
    public function isAuthoredByCurrentViewer(): bool
    {
        try {
            $userId = auth()->id();
            if ($userId) {
                return $this->user_id && (int)$this->user_id === (int)$userId;
            }
        } catch (\Throwable $e) {
            // Auth not available
        }

        try {
            if (session()->isStarted()) {
                $sessionId = session()->getId();
                return $this->session_id && $this->session_id === $sessionId;
            }
        } catch (\Throwable $e) {
            // Session not started
        }

        return false;
    }
}

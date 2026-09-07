<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RestaurantBlog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'restaurant_blogs';

    protected $fillable = [
        'restaurant_id',
        'food_category_id',
        'cuisine_id',
        'title',
        'slug',
        'short_description',
        'content',
        'featured_image',
        'status',
        'approval_status',
        'admin_remarks',
        'approved_by',
        'approved_at',
        'views_count',
        'likes_count',
        'dislikes_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_INACTIVE = 'inactive';

    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    /**
     * Restaurant owning this blog.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    /**
     * Category for this blog.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }

    /**
     * Cuisine for this blog.
     */
    public function cuisine(): BelongsTo
    {
        return $this->belongsTo(Cuisine::class, 'cuisine_id');
    }

    /**
     * User who created the blog.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Admin who approved/rejected the blog.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Comments on this blog.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(RestaurantBlogComment::class, 'restaurant_blog_id');
    }

    /**
     * Top-level approved comments with replies.
     */
    public function rootComments(): HasMany
    {
        return $this->hasMany(RestaurantBlogComment::class, 'restaurant_blog_id')
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->with(['replies.user', 'user'])
            ->latest();
    }

    /**
     * Reactions (Likes/Dislikes) on this blog.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(RestaurantBlogReaction::class, 'restaurant_blog_id');
    }

    /**
     * Get current viewer's reaction ('like', 'dislike', or null).
     */
    public function getCurrentUserReaction(): ?string
    {
        $query = $this->reactions();

        try {
            $userId = auth()->id();
            if ($userId) {
                $reaction = $query->where('user_id', $userId)->first();
                return $reaction?->reaction_type;
            }
        } catch (\Throwable $e) {
            // Auth not available in some contexts
        }

        try {
            if (session()->isStarted()) {
                $sessionId = session()->getId();
                if ($sessionId) {
                    $reaction = $query->where('session_id', $sessionId)->first();
                    return $reaction?->reaction_type;
                }
            }
        } catch (\Throwable $e) {
            // Session not available
        }

        return null;
    }

    /**
     * Scope: Live blogs visible on storefront (published + approved).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->where('approval_status', self::APPROVAL_APPROVED);
    }

    /**
     * Scope: Pending admin review.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_PENDING);
    }

    /**
     * Scope: Approved by admin.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_APPROVED);
    }

    /**
     * Scope: Rejected by admin.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('approval_status', self::APPROVAL_REJECTED);
    }

    /**
     * Check if blog is approved.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    /**
     * Check if blog is pending review.
     */
    public function isPending(): bool
    {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    /**
     * Check if blog is rejected.
     */
    public function isRejected(): bool
    {
        return $this->approval_status === self::APPROVAL_REJECTED;
    }

    /**
     * Check if blog is published.
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if blog is live on storefront.
     */
    public function isLive(): bool
    {
        return $this->isPublished() && $this->isApproved();
    }

    /**
     * Calculate estimated reading time in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content ?? ''));
        return max(1, (int) ceil($words / 200));
    }

    /**
     * Generate unique slug for a blog title.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

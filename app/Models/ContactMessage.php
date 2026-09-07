<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'ip_address',
    ];

    public const STATUS_UNREAD = 'unread';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_CLOSED = 'closed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ContactMessageReply::class, 'contact_message_id')->latest();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }
}

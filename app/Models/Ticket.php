<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_id', 'user_type', 'user_id', 'restaurant_id', 'order_id',
        'category', 'subject', 'description', 'attachment', 'priority',
        'status', 'resolution_note', 'rating', 'assigned_to',
        'resolved_at', 'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'rating' => 'integer',
    ];

    public const STATUSES = ['open', 'in_progress', 'resolved', 'closed', 'reopened'];
    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];
    public const CATEGORIES = [
        'order' => 'Order Issue',
        'payment' => 'Payment Issue',
        'delivery' => 'Delivery Issue',
        'account' => 'Account Issue',
        'other' => 'Other',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(TicketNotification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['closed']);
    }

    public function raiserName(): string
    {
        if ($this->user_type === 'restaurant') {
            $restaurant = $this->restaurant_id
                ? Restaurant::find($this->restaurant_id)
                : null;

            if (! $restaurant) {
                $restaurant = Restaurant::where('user_id', $this->user_id)->first();
            }

            if ($restaurant && !empty($restaurant->restaurant_name)) {
                return $restaurant->restaurant_name;
            }
        }

        return $this->user?->name ?? ucfirst($this->user_type);
    }

    public function scopeOpen($q)
    {
        return $q->where('status', 'open');
    }

    public function markStatus(string $status, ?string $resolutionNote = null): void
    {
        $this->status = $status;

        if ($status === 'resolved') {
            $this->resolved_at = now();
            if ($resolutionNote) {
                $this->resolution_note = $resolutionNote;
            }
        }

        if ($status === 'closed') {
            $this->closed_at = now();
        }

        $this->save();
    }
}

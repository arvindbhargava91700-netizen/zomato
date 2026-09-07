<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class TicketNotification extends Model
{
    /**
     * Store an in-app notification and (optionally) send an email copy.
     */
    public static function notify(
        Ticket $ticket,
        string $toType,
        int $toId,
        string $title,
        string $message,
        ?string $email = null
    ): void {
        self::create([
            'ticket_id' => $ticket->id,
            'to_type' => $toType,
            'to_id' => $toId,
            'title' => $title,
            'message' => $message,
        ]);

        if ($email) {
            try {
                Mail::raw($message, function ($m) use ($email, $title) {
                    $m->to($email)->subject($title);
                });
            } catch (\Throwable $e) {
                // email sending is best-effort; ignore failures
            }
        }
    }

    protected $fillable = [
        'ticket_id', 'to_type', 'to_id', 'title', 'message', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}

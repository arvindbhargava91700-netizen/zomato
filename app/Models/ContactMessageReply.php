<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessageReply extends Model
{
    use HasFactory;

    protected $table = 'contact_message_replies';

    protected $fillable = [
        'contact_message_id',
        'admin_id',
        'email_to',
        'subject',
        'message',
        'status',
        'error_message',
    ];

    public function contactMessage(): BelongsTo
    {
        return $this->belongsTo(ContactMessage::class, 'contact_message_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}

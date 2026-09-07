<?php

namespace App\Events;

use App\Models\TicketMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public TicketMessage $message)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('ticket.' . $this->message->ticket->ticket_id),
        ];
    }

    public function broadcastWith(): array
    {
        $attachment = $this->message->attachment;
        $ext = $attachment ? strtolower(pathinfo($attachment, PATHINFO_EXTENSION)) : null;
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);

        return [
            'id' => $this->message->id,
            'ticket_id' => $this->message->ticket->ticket_id,
            'sender_type' => $this->message->sender_type,
            'sender_id' => $this->message->sender_id,
            'message' => $this->message->message,
            'attachment' => $attachment ? asset('storage/' . $attachment) : null,
            'attachment_name' => $attachment ? basename($attachment) : null,
            'attachment_is_image' => $isImage,
            'created_at' => $this->message->created_at->format('d M, h:i A'),
            'created_date' => $this->message->created_at->format('Y-m-d'),
            'created_date_label' => $this->message->created_at->format('d M Y'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.message';
    }
}

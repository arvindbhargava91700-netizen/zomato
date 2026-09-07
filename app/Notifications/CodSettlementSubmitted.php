<?php

namespace App\Notifications;

use App\Models\CodSettlement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CodSettlementSubmitted extends Notification
{
    use Queueable;

    public function __construct(public CodSettlement $settlement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New COD Settlement',
            'message' => 'Delivery partner ' . ($this->settlement->deliveryPartner?->name ?? '-')
                . ' submitted a COD settlement of ' . number_format((float) $this->settlement->amount, 2)
                . ' for Order #' . $this->settlement->order_id . '.',
            'url' => route('admin.settlements.index', ['status' => 'pending']),
            'settlement_id' => $this->settlement->id,
        ];
    }
}
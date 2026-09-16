<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Order Received',
            'message' => 'You have received a new order (#' . $this->order->id . ') for ₹' . number_format($this->order->total, 2) . '.',
            'url' => route('restaurant.orders.show', $this->order->id),
            'order_id' => $this->order->id,
        ];
    }
}

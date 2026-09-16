<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewDeliveryAssignedNotification extends Notification
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
            'title' => 'New Delivery Assigned',
            'message' => 'You have been assigned to deliver order #' . $this->order->id . ' from ' . ($this->order->restaurant->restaurant_name ?? 'Restaurant') . '.',
            'url' => route('delivery-partner.orders.show', $this->order->id),
            'order_id' => $this->order->id,
            'icon' => 'feather-truck'
        ];
    }
}

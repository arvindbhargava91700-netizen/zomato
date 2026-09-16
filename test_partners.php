<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::find(7); // Assuming order 7 is the one they tested
if (!$order) {
    echo "Order #7 not found.\n";
    exit;
}

$tried = $order->deliveryRequests()
    ->where('status', \App\Models\DeliveryRequest::STATUS_REJECTED)
    ->pluck('delivery_partner_id')->all();
    
if ($order->delivery_partner_id) {
    $tried[] = $order->delivery_partner_id;
}
$tried = array_unique($tried);

echo "Tried (Rejected/Assigned) partners: " . implode(', ', $tried) . "\n";

$busy = \App\Models\Order::whereIn('status', [
    \App\Models\Order::STATUS_ASSIGNED,
    \App\Models\Order::STATUS_PICKED_UP,
    \App\Models\Order::STATUS_OUT_FOR_DELIVERY,
])->whereNotNull('delivery_partner_id')->pluck('delivery_partner_id')->all();

echo "Busy partners: " . implode(', ', $busy) . "\n";

$partners = \App\Models\User::whereHas('role', function ($q) {
        $q->where('slug', 'delivery_partner');
    })
    ->whereNotIn('id', array_merge($tried, $busy))
    ->with('addresses')
    ->get(['id', 'name']);

echo "Available partners for assignment:\n";
foreach($partners as $p) {
    echo "ID: {$p->id}, Name: {$p->name}\n";
}

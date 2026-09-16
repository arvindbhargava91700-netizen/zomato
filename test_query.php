<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$lat = 19.0760;
$lng = 72.8777;
$haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(addresses.latitude)) * cos(radians(addresses.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(addresses.latitude))))";

$partners = \App\Models\User::select('users.*')
    ->whereHas('role', function ($q) {
        $q->where('slug', 'delivery_partner');
    })
    ->leftJoin('addresses', 'users.id', '=', 'addresses.user_id')
    ->selectRaw('MIN(' . $haversine . ') AS distance')
    ->groupBy('users.id')
    ->orderByRaw('MIN(addresses.latitude) IS NULL, distance ASC')
    ->get();

print_r($partners->pluck('name', 'distance')->toArray());

<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Restaurant;
use App\Models\DiningOffer;
use App\Models\Booking;
use Illuminate\Http\Request;

echo "=== Testing Table Booking & Cover Charge Flow ===\n";

$restaurant = Restaurant::with('diningOffers')->where('restaurant_slug', 'spice-hub-restaurant')->first();
if (!$restaurant) {
    echo "Restaurant not found\n";
    exit(1);
}

echo "Found Restaurant: {$restaurant->restaurant_name} (ID: {$restaurant->id})\n";
$offer = $restaurant->diningOffers->first();
if ($offer) {
    echo "Offer: {$offer->title} | Cover Charge: {$offer->cover_charge}\n";
}

// Test Controller store method
$controller = new App\Http\Controllers\frontController();

// 1. Booking WITH Offer having Cover Charge
$reqWithCover = Request::create('/book-table', 'POST', [
    'restaurant_id' => $restaurant->id,
    'dining_offer_id' => $offer ? $offer->id : null,
    'customer_name' => 'John Doe',
    'phone' => '9876543210',
    'book_date' => date('Y-m-d', strtotime('+1 day')),
    'book_time' => '20:00',
    'guests' => 4,
    'payment_method' => 'upi',
]);

$responseWithCover = $controller->bookTableStore($reqWithCover);
$data1 = $responseWithCover->getData(true);
echo "\n--- Result 1 (With Cover Charge) ---\n";
print_r($data1);

// 2. Booking WITHOUT Offer (Free)
$reqFree = Request::create('/book-table', 'POST', [
    'restaurant_id' => $restaurant->id,
    'dining_offer_id' => null,
    'customer_name' => 'Jane Smith',
    'phone' => '9123456780',
    'book_date' => date('Y-m-d', strtotime('+2 days')),
    'book_time' => '19:00',
    'guests' => 2,
]);

$responseFree = $controller->bookTableStore($reqFree);
$data2 = $responseFree->getData(true);
echo "\n--- Result 2 (Without Cover Charge) ---\n";
print_r($data2);

echo "\nVerification Complete!\n";

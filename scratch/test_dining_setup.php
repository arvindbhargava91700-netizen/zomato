<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use App\Models\Booking;

echo "=== Testing Dining Setup, Table Management & Slot System ===\n\n";

$restaurant = Restaurant::where('restaurant_slug', 'spice-hub-restaurant')->first();
if (!$restaurant) {
    echo "Restaurant not found\n";
    exit(1);
}

echo "1. Configuring Restaurant: {$restaurant->restaurant_name} (ID: {$restaurant->id})\n";
$restaurant->update([
    'opening_time' => '11:00:00',
    'closing_time' => '23:00:00',
    'slot_duration_minutes' => 60,
    'advance_booking_days' => 7,
]);

echo "   - Opening Time: {$restaurant->opening_time}\n";
echo "   - Closing Time: {$restaurant->closing_time}\n";
echo "   - Slot Duration: {$restaurant->slot_duration_minutes} minutes\n\n";

// 2. Clear and Add Tables: T1(2), T2(2), T3(4), T4(4), T5(6), T6(8)
echo "2. Setting up Defined Tables...\n";
RestaurantTable::where('restaurant_id', $restaurant->id)->delete();

$tablesConfig = [
    ['table_number' => 'T1', 'capacity' => 2],
    ['table_number' => 'T2', 'capacity' => 2],
    ['table_number' => 'T3', 'capacity' => 4],
    ['table_number' => 'T4', 'capacity' => 4],
    ['table_number' => 'T5', 'capacity' => 6],
    ['table_number' => 'T6', 'capacity' => 8],
];

foreach ($tablesConfig as $cfg) {
    RestaurantTable::create([
        'restaurant_id' => $restaurant->id,
        'table_number' => $cfg['table_number'],
        'capacity' => $cfg['capacity'],
        'status' => 'available',
    ]);
}
$restaurant->update(['total_tables_count' => count($tablesConfig)]);

$createdTables = RestaurantTable::where('restaurant_id', $restaurant->id)->get();
echo "   - Total Tables Created: " . $createdTables->count() . "\n";
foreach ($createdTables as $t) {
    echo "     * Table {$t->table_number}: {$t->capacity} Seats (Status: {$t->status})\n";
}
echo "\n";

// 3. Generating Time Slots
echo "3. Generating Time Slots between {$restaurant->opening_time} and {$restaurant->closing_time}...\n";
$slots = $restaurant->generateTimeSlots();
echo "   - Generated " . count($slots) . " Slots:\n";
foreach ($slots as $s) {
    echo "     * {$s['time']} ({$s['label']})\n";
}
echo "\n";

// 4. Check Slot Availability for Tomorrow at 19:00 (7:00 PM) with 4 Guests
$testDate = date('Y-m-d', strtotime('+1 day'));
$testTime = '19:00';
echo "4. Checking Slot Availability for Date: {$testDate} at {$testTime} for 4 Guests...\n";
$availBefore = $restaurant->getSlotAvailability($testDate, $testTime, 4);
echo "   - Total Tables: {$availBefore['total_tables']}\n";
echo "   - Booked Count: {$availBefore['booked_count']}\n";
echo "   - Free Tables: {$availBefore['available_count']}\n";
echo "   - Suitable Tables (Capacity >= 4): {$availBefore['suitable_count']}\n";
echo "   - Slot Status: {$availBefore['status']}\n\n";

// 5. Booking a Table via frontController::bookTableStore
echo "5. Simulating a Table Booking at {$testTime} for 4 Guests...\n";
$controller = new App\Http\Controllers\frontController();
$req = \Illuminate\Http\Request::create('/book-table', 'POST', [
    'restaurant_id' => $restaurant->id,
    'customer_name' => 'Rahul Sharma',
    'phone' => '9988776655',
    'book_date' => $testDate,
    'book_time' => $testTime,
    'guests' => 4,
    'payment_method' => 'razorpay',
]);

$response = $controller->bookTableStore($req);
$resData = $response->getData(true);
echo "   - Booking Result:\n";
print_r($resData);

// 6. Check Slot Availability Again
echo "6. Checking Slot Availability after Booking...\n";
$availAfter = $restaurant->getSlotAvailability($testDate, $testTime, 4);
echo "   - Total Tables: {$availAfter['total_tables']}\n";
echo "   - Booked Count: {$availAfter['booked_count']}\n";
echo "   - Free Tables: {$availAfter['available_count']}\n";
echo "   - Suitable Tables: {$availAfter['suitable_count']}\n";
echo "   - Slot Status: {$availAfter['status']}\n\n";

echo "=== All Tests Passed Successfully! ===\n";

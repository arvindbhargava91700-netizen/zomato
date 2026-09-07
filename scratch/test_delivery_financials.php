<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Income;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\DB;

echo "--- Testing Delivery Financial Distribution ---\n";

DB::beginTransaction();

try {
    // 1. Get or create a sample delivery partner & restaurant owner
    $partner = User::whereHas('role', fn($q) => $q->where('slug', 'delivery_partner'))->first();
    $restaurant = Restaurant::first();

    if (!$partner || !$restaurant) {
        throw new Exception("Partner or restaurant missing");
    }

    $partnerWallet = $partner->getOrCreateWallet();
    $initialPartnerBalance = (float) $partnerWallet->balance;

    $restaurantOwner = User::find($restaurant->user_id);
    $restaurantWallet = $restaurantOwner->getOrCreateWallet();
    $initialRestaurantBalance = (float) $restaurantWallet->balance;

    echo "Initial Partner Balance: {$initialPartnerBalance}\n";
    echo "Initial Restaurant Balance: {$initialRestaurantBalance}\n";

    // 2. Create a dummy test order
    $order = Order::create([
        'user_id' => 1,
        'restaurant_id' => $restaurant->id,
        'delivery_partner_id' => $partner->id,
        'delivery_option' => 'delivery',
        'delivery_charge' => 50.00,
        'payment_method' => 'cash_on_delivery',
        'payment_status' => 'pending',
        'subtotal' => 400.00,
        'discount' => 0.00,
        'tax' => 20.00,
        'total' => 470.00, // 400 food + 50 delivery + 20 tax
        'status' => Order::STATUS_OUT_FOR_DELIVERY,
    ]);

    echo "Created Order #{$order->id} (Total: {$order->total}, COD)\n";

    // 3. Mark delivered & distribute financials
    $financials = $order->distributeDeliveryFinancials();
    echo "Financials calculated:\n";
    print_r($financials);

    $partnerWallet->refresh();
    $restaurantWallet->refresh();

    echo "Partner Balance After Delivery: {$partnerWallet->balance}\n";
    echo "Restaurant Balance After Delivery: {$restaurantWallet->balance}\n";

    // Partner should have: initial - 470.00 (COD collected) + 42.50 (85% of 50 delivery charge) = initial - 427.50
    $expectedPartnerDiff = round(-470.00 + 42.50, 2);
    $actualPartnerDiff = round($partnerWallet->balance - $initialPartnerBalance, 2);
    echo "Partner expected diff: {$expectedPartnerDiff}, actual: {$actualPartnerDiff}\n";

    // Restaurant should have: initial + (400 - 10% commission = 360.00)
    $expectedRestDiff = 360.00;
    $actualRestDiff = round($restaurantWallet->balance - $initialRestaurantBalance, 2);
    echo "Restaurant expected diff: {$expectedRestDiff}, actual: {$actualRestDiff}\n";

    if ($actualPartnerDiff == $expectedPartnerDiff && $actualRestDiff == $expectedRestDiff) {
        echo ">>> SUCCESS: Delivery financials and wallet logic verified perfectly! <<<\n";
    } else {
        echo ">>> MISMATCH detected! <<<\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
} finally {
    // Roll back changes so database remains clean
    DB::rollBack();
    echo "Transaction rolled back cleanly.\n";
}

<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== Testing Restaurant Dining Setup View Rendering ===\n\n";

$user = User::where('email', 'like', '%spice%')->orWhere('id', 1)->first();
if ($user) {
    Auth::login($user);
}

$restaurant = Restaurant::with('tables')->first();
$controller = new App\Http\Controllers\Restaurant\DiningSetupController();
$request = \Illuminate\Http\Request::create('/dining-setup', 'GET');

try {
    $view = $controller->index($request);
    $html = $view->render();
    echo "Rendered Dining Setup View Successfully! Length: " . strlen($html) . " bytes\n";
    echo "Contains 'Dining Setup & Table Management': " . (str_contains($html, 'Dining Setup & Table Management') ? 'YES' : 'NO') . "\n";
    echo "Contains 'Restaurant Tables': " . (str_contains($html, 'Restaurant Tables') ? 'YES' : 'NO') . "\n";
    echo "Contains 'Live Slot Availability Inspector': " . (str_contains($html, 'Live Slot Availability Inspector') ? 'YES' : 'NO') . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

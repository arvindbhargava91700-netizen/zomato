<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Restaurant;
use Illuminate\Http\Request;

$controller = new App\Http\Controllers\frontController();
$r2 = Restaurant::where('id', '!=', 1)->first();
if ($r2) {
    $request2 = Request::create('/menu-listing?restaurant_id=' . $r2->id, 'GET');
    $response2 = $controller->menuListing($request2);
    $rendered2 = $response2->render();
    echo "Restaurant {$r2->id} reviews count in DB: " . $r2->reviews()->count() . "\n";
    if (str_contains($rendered2, 'Gunjan Puri')) {
        echo "Successfully found Gunjan Puri for restaurant without reviews!\n";
    } else {
        echo "Gunjan Puri not found for restaurant {$r2->id}\n";
    }
}

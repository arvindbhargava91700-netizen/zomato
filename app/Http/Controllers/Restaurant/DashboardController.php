<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Restaurant Owner Dashboard.
     */
    public function index(): View
    {
        $owner = auth()->user();
        $restaurant = Restaurant::where('user_id', $owner?->id)->first();

        $stats = [
            'todays_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0,
            'total_foods' => 0,
            'total_categories' => 0,
            'todays_earnings' => 0.00,
            'monthly_earnings' => 0.00,
            'total_earnings' => 0.00,
        ];

        if ($restaurant) {
            $base = Order::where('restaurant_id', $restaurant->id);

            $stats['todays_orders'] = (clone $base)->whereDate('created_at', today())->count();
            $stats['pending_orders'] = (clone $base)->where('status', Order::STATUS_PENDING)->count();
            $stats['completed_orders'] = (clone $base)->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])->count();
            $stats['cancelled_orders'] = (clone $base)->whereIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELLED])->count();
            $stats['total_foods'] = $restaurant->foods()->count();
            $stats['total_categories'] = $restaurant->categories()->count();

            $incomes = Income::where('recipient_type', Income::RECIPIENT_RESTAURANT)
                ->where('recipient_id', $owner->id);

            $stats['todays_earnings'] = (float) (clone $incomes)->whereDate('paid_at', today())->sum('amount');
            $stats['monthly_earnings'] = (float) (clone $incomes)
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount');
            $stats['total_earnings'] = (float) (clone $incomes)->sum('amount');
        }

        $recentOrders = $restaurant
            ? Order::with(['user', 'items'])
                ->where('restaurant_id', $restaurant->id)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get()
            : collect();

        return view('manage.restaurant.dashboard', compact('owner', 'restaurant', 'stats', 'recentOrders'));
    }
}
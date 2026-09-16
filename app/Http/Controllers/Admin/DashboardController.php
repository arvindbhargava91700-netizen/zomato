<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Income;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(): View
    {
        $title = getPageTitle("Dashboard");
        $orders = Order::query();

        $stats = [
            'todays_orders' => (clone $orders)->whereDate('created_at', today())->count(),
            'platform_earnings' => (float) Income::where('recipient_type', Income::RECIPIENT_PLATFORM)->sum('amount'),
            'delivered_orders' => (clone $orders)->where('status', Order::STATUS_DELIVERED)->count(),
            'active_orders' => (clone $orders)->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_ACCEPTED,
                Order::STATUS_PREPARING,
                Order::STATUS_READY,
                Order::STATUS_ASSIGNED,
                Order::STATUS_PICKED_UP,
                Order::STATUS_OUT_FOR_DELIVERY,
            ])->count(),
            'restaurants' => Restaurant::count(),
            'delivery_partners' => User::whereHas('role', fn ($q) => $q->where('slug', 'delivery_partner'))->count(),
            'customers' => User::whereHas('role', fn ($q) => $q->where('slug', 'customer'))->count(),
            'total_foods' => Food::count(),
        ];

        return view('manage.admin.dashboard', compact('stats', 'title'));
    }
}
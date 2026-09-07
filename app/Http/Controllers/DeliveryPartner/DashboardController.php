<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\DeliveryRequest;
use App\Models\Income;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Delivery Partner Dashboard.
     */
    public function index(): View
    {
        $partner = auth()->user();

        // Expire stale requests, then try the next partner for orders that lost theirs
        $expiredOrderIds = Order::expireStaleRequests();
        if (!empty($expiredOrderIds)) {
            Order::whereIn('id', $expiredOrderIds)
                ->where('status', Order::STATUS_READY)
                ->whereNull('delivery_partner_id')
                ->get()
                ->each->sendDeliveryRequests();
        }

        $pendingRequests = DeliveryRequest::with(['order.restaurant', 'order.items', 'order.user'])
            ->where('delivery_partner_id', $partner?->id)
            ->where('status', DeliveryRequest::STATUS_PENDING)
            ->latest()
            ->get();

        $activeDelivery = Order::with(['restaurant', 'user', 'address'])
            ->where('delivery_partner_id', $partner?->id)
            ->whereIn('status', [Order::STATUS_ASSIGNED, Order::STATUS_PICKED_UP, Order::STATUS_OUT_FOR_DELIVERY])
            ->latest()
            ->first();

        $base = Order::where('delivery_partner_id', $partner?->id);

        $incomes = Income::where('recipient_type', Income::RECIPIENT_DELIVERY_PARTNER)
            ->where('recipient_id', $partner?->id);

        $stats = [
            'todays_deliveries' => (clone $base)->whereDate('created_at', today())->count(),
            'pending_pickups' => (clone $base)->whereIn('status', [Order::STATUS_ASSIGNED, Order::STATUS_PICKED_UP, Order::STATUS_OUT_FOR_DELIVERY])->count(),
            'completed_deliveries' => (clone $base)->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])->count(),
            'cancelled_deliveries' => (clone $base)->whereIn('status', [Order::STATUS_REJECTED, Order::STATUS_CANCELLED])->count(),
            'rating' => number_format((clone $base)
                ->whereHas('review', fn ($q) => $q->whereNotNull('delivery_rating'))
                ->with('review')
                ->get()
                ->avg(fn ($o) => $o->review?->delivery_rating) ?? 0, 1),
            'todays_earnings' => (float) (clone $incomes)->whereDate('paid_at', today())->sum('amount'),
            'monthly_earnings' => (float) (clone $incomes)
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'total_earnings' => (float) (clone $incomes)->sum('amount'),
        ];

        $recentDeliveries = Order::with(['restaurant', 'user', 'latestCodSettlement'])
            ->where('delivery_partner_id', $partner?->id)
            ->latest()
            ->take(5)
            ->get();

        return view('manage.delivery-partner.dashboard', compact(
            'partner',
            'pendingRequests',
            'activeDelivery',
            'stats',
            'recentDeliveries'
        ));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Restaurant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of all payment transactions with filters and metrics.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with(['booking', 'order', 'restaurant', 'customer']);

        // Filter: Keyword Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                    ->orWhere('payment_id', 'like', "%{$search}%")
                    ->orWhere('order_reference_id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter: Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter: Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter: Restaurant
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter: Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Metric calculations based on filtered/unfiltered data
        $metricsQuery = clone $query;
        $totalTransactions = $metricsQuery->count();
        $totalVolume = (float) $metricsQuery->sum('total_amount');
        $totalAdminShare = (float) $metricsQuery->sum('admin_amount');
        $totalRestaurantShare = (float) $metricsQuery->sum('restaurant_amount');
        $tableBookingsCount = (clone $metricsQuery)->where('type', Transaction::TYPE_TABLE_BOOKING)->count();

        // Paginated list
        $transactions = $query->latest()->paginate(15)->withQueryString();

        // List of restaurants for filter dropdown
        $restaurants = Restaurant::orderBy('restaurant_name')->get(['id', 'restaurant_name']);
        $companySetting = CompanySetting::firstSetting();

        return view('manage.admin.transactions.index', compact(
            'transactions',
            'restaurants',
            'companySetting',
            'totalTransactions',
            'totalVolume',
            'totalAdminShare',
            'totalRestaurantShare',
            'tableBookingsCount'
        ));
    }

    /**
     * Display the specified transaction details.
     */
    public function show(Transaction $transaction): View
    {
        $transaction->load(['booking.table', 'booking.diningOffer', 'order', 'restaurant', 'customer']);
        $companySetting = CompanySetting::firstSetting();

        return view('manage.admin.transactions.show', compact('transaction', 'companySetting'));
    }
}

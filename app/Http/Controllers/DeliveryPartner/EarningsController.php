<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\View\View;

class EarningsController extends Controller
{
    /**
     * Display the delivery partner earnings listing (from the incomes table).
     */
public function index(): View
{
    $partner = auth()->user();

    $incomes = Income::with('order.restaurant')
        ->where('recipient_type', Income::RECIPIENT_DELIVERY_PARTNER)
        ->where('recipient_id', $partner->id)
        ->latest('paid_at')
        ->get();

    $rows = $incomes->map(fn ($income) => [
        'order_id'    => $income->order_id,
        'restaurant'  => $income->order?->restaurant?->restaurant_name ?? '-',
        'base'        => (float) $income->base_amount,
        'percentage'  => (float) $income->percentage,
        'amount'      => (float) $income->amount,
        'paid_at'     => $income->paid_at,
    ]);

    $stats = [
        'todays'  => $incomes->where('paid_at', '>=', today())->sum('amount'),
        'monthly' => $incomes->where('paid_at', '>=', now()->startOfMonth())->sum('amount'),
        'total'   => $incomes->sum('amount'),
    ];

    return view('manage.delivery-partner.earnings.index', compact('rows', 'stats'));
}
}
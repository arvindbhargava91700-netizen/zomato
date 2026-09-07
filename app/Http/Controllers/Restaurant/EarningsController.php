<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\View\View;

class EarningsController extends Controller
{
    /**
     * Display the restaurant owner earnings listing (from the incomes table).
     */
    public function index(): View
    {
        $owner = auth()->user();

        $incomes = Income::with(['order.restaurant'])
            ->where('recipient_type', Income::RECIPIENT_RESTAURANT)
            ->where('recipient_id', $owner?->id)
            ->latest('paid_at')
            ->get();

        $rows = $incomes->map(fn (Income $income) => [
            'order_id' => $income->order_id,
            'restaurant' => $income->order?->restaurant?->restaurant_name ?? '-',
            'base' => (float) $income->base_amount,
            'percentage' => (float) $income->percentage,
            'amount' => (float) $income->amount,
            'paid_at' => $income->paid_at,
        ])->all();

        $stats = [
            'todays' => collect($rows)->filter(fn ($r) => $r['paid_at'] && $r['paid_at']->isToday())->sum('amount'),
            'monthly' => collect($rows)->filter(fn ($r) => $r['paid_at'] && $r['paid_at']->isCurrentMonth())->sum('amount'),
            'total' => collect($rows)->sum('amount'),
        ];

        return view('manage.restaurant.earnings.index', compact('rows', 'stats'));
    }
}
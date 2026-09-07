<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function commission(): View
    {
        // All platform incomes (one query)
        $allIncomes = Income::with(['order.restaurant', 'booking.restaurant', 'restaurant'])
            ->where('recipient_type', 'platform')
            ->get();

        // Only the commission incomes
        $incomes = $allIncomes->where('income_type', 'restaurant_commission');

        // Build the table rows
        $rows = [];
        foreach ($incomes as $income) {
            $ref = $income->order_id ? "Order #{$income->order_id}" : ($income->booking_id ? "Booking #{$income->booking_id}" : '-');
            $rows[] = [
                'order_id' => $ref,
                'restaurant' => $income->order?->restaurant?->restaurant_name ?? $income->booking?->restaurant?->restaurant_name ?? $income->restaurant?->restaurant_name ?? '-',
                'paid_at' => $income->paid_at?->format('d M Y'),
                'base' => (float) $income->base_amount,
                'percentage' => (float) $income->percentage,
                'amount' => (float) $income->amount,
            ];
        }

        // Totals for the three summary cards
        $summary = [
            'commission' => $allIncomes->where('income_type', 'restaurant_commission')->sum('amount'),
            'tax' => $allIncomes->where('income_type', 'tax_gst')->sum('amount'),
            'platform' => $allIncomes->where('income_type', 'platform_share')->sum('amount'),
        ];

        return view('manage.admin.earnings.index', [
            'type' => 'commission',
            'rows' => $rows,
            'total' => $incomes->sum('amount'),
            'summary' => $summary,
            'grandTotal' => array_sum($summary),
        ]);
    }

    public function tax(): View
    {
        // All platform incomes (one query)
        $allIncomes = Income::with(['order.restaurant', 'booking.restaurant', 'restaurant'])
            ->where('recipient_type', 'platform')
            ->get();

        // Only the tax / gst incomes
        $incomes = $allIncomes->where('income_type', 'tax_gst');

        // Build the table rows
        $rows = [];
        foreach ($incomes as $income) {
            $ref = $income->order_id ? "Order #{$income->order_id}" : ($income->booking_id ? "Booking #{$income->booking_id}" : '-');
            $rows[] = [
                'order_id' => $ref,
                'restaurant' => $income->order?->restaurant?->restaurant_name ?? $income->booking?->restaurant?->restaurant_name ?? $income->restaurant?->restaurant_name ?? '-',
                'paid_at' => $income->paid_at?->format('d M Y'),
                'base' => (float) $income->base_amount,
                'percentage' => (float) $income->percentage,
                'amount' => (float) $income->amount,
            ];
        }

        // Totals for the three summary cards
        $summary = [
            'commission' => $allIncomes->where('income_type', 'restaurant_commission')->sum('amount'),
            'tax' => $allIncomes->where('income_type', 'tax_gst')->sum('amount'),
            'platform' => $allIncomes->where('income_type', 'platform_share')->sum('amount'),
        ];

        return view('manage.admin.earnings.index', [
            'type' => 'tax',
            'rows' => $rows,
            'total' => $incomes->sum('amount'),
            'summary' => $summary,
            'grandTotal' => array_sum($summary),
        ]);
    }

    public function platform(): View
    {
        // All platform incomes (one query)
        $allIncomes = Income::with(['order.restaurant', 'booking.restaurant', 'restaurant'])
            ->where('recipient_type', 'platform')
            ->get();

        // Only the platform share incomes
        $incomes = $allIncomes->where('income_type', 'platform_share');

        // Build the table rows
        $rows = [];
        foreach ($incomes as $income) {
            $ref = $income->order_id ? "Order #{$income->order_id}" : ($income->booking_id ? "Booking #{$income->booking_id}" : '-');
            $rows[] = [
                'order_id' => $ref,
                'restaurant' => $income->order?->restaurant?->restaurant_name ?? $income->booking?->restaurant?->restaurant_name ?? $income->restaurant?->restaurant_name ?? '-',
                'paid_at' => $income->paid_at?->format('d M Y'),
                'base' => (float) $income->base_amount,
                'percentage' => (float) $income->percentage,
                'amount' => (float) $income->amount,
            ];
        }

        // Totals for the three summary cards
        $summary = [
            'commission' => $allIncomes->where('income_type', 'restaurant_commission')->sum('amount'),
            'tax' => $allIncomes->where('income_type', 'tax_gst')->sum('amount'),
            'platform' => $allIncomes->where('income_type', 'platform_share')->sum('amount'),
        ];

        return view('manage.admin.earnings.index', [
            'type' => 'platform',
            'rows' => $rows,
            'total' => $incomes->sum('amount'),
            'summary' => $summary,
            'grandTotal' => array_sum($summary),
        ]);
    }
}
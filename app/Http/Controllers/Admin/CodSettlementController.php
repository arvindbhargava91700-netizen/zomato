<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CodSettlement;
use App\Models\CompanySetting;
use App\Models\Income;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CodSettlementController extends Controller
{
    /**
     * Display a listing of COD settlements with status filter.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $settlements = CodSettlement::with(['order', 'deliveryPartner', 'reviewer'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pendingCount = CodSettlement::where('status', 'pending')->count();
        $paidCount = CodSettlement::where('status', 'paid')->count();
        $rejectedCount = CodSettlement::where('status', 'rejected')->count();
        $totalAmount = CodSettlement::where('status', 'paid')->sum('amount');

        return view('manage.admin.settlements.index', compact(
            'settlements',
            'status',
            'pendingCount',
            'paidCount',
            'rejectedCount',
            'totalAmount'
        ));
    }

    /**
     * Display a single COD settlement for review (proof, transaction id, etc.).
     */
    public function show($id): View
    {
        $settlement = CodSettlement::with(['order', 'deliveryPartner', 'reviewer'])->findOrFail($id);

        $order = $settlement->order;
        $setting = CompanySetting::firstSetting();

        $foodTotal = round((float) $order->subtotal - (float) $order->discount, 2);
        $deliveryCharge = (float) $order->delivery_charge;
        $tax = (float) $order->tax;

        $commissionPct = (float) ($order->restaurant?->commission_percentage ?: 10);
        $partnerSharePct = (float) ($setting->delivery_partner_share ?: 85);
        $platformSharePct = (float) ($setting->platform_share ?: 15);

        $commission = round($foodTotal * $commissionPct / 100, 2);

        $payout = [
            'food_total' => $foodTotal,
            'delivery_charge' => $deliveryCharge,
            'tax' => $tax,
            'commission_pct' => $commissionPct,
            'commission' => $commission,
            'partner_share_pct' => $partnerSharePct,
            'platform_share_pct' => $platformSharePct,
            'restaurant_payout' => round($foodTotal - $commission, 2),
            'delivery_partner_payout' => round($deliveryCharge * $partnerSharePct / 100, 2),
            'platform_payout' => round($deliveryCharge * $platformSharePct / 100 + $tax + $commission, 2),
        ];

        return view('manage.admin.settlements.show', [
            'settlement' => $settlement,
            'payout' => $payout,
        ]);
    }

    /**
     * Confirm a pending COD settlement. Payment is marked as paid.
     */
    public function confirm(Request $request, $id): RedirectResponse
    {
        $settlement = CodSettlement::with('order.restaurant')->findOrFail($id);

        if (!$settlement->isPending()) {
            return back()->with('error', 'This settlement has already been reviewed.');
        }

        $data = $request->validate([
            'remark' => ['nullable', 'string', 'max:500'],
        ]);

        $payout = $settlement->payoutBreakdown();

        $settlement->update([
            'status' => CodSettlement::STATUS_PAID,
            'remark' => $data['remark'] ?? $settlement->remark,
            'reviewed_at' => now(),
            'reviewed_by' => auth('admin')->id(),
            'restaurant_payout' => $payout['restaurant_payout'],
            'delivery_partner_payout' => $payout['delivery_partner_payout'],
            'platform_payout' => $payout['platform_payout'],
        ]);

        $settlement->order()->update([
            'payment_status' => 'paid',
        ]);

        $this->recordIncomes($settlement, $payout);

        // Credit Delivery Partner Wallet for confirmed COD cash deposit (clearing the COD cash-in-hand debt)
        if ($settlement->delivery_partner_id && (float) $settlement->amount > 0) {
            $deliveryPartner = User::find($settlement->delivery_partner_id);
            if ($deliveryPartner) {
                $deliveryPartner->getOrCreateWallet()->credit(
                    (float) $settlement->amount,
                    'cod_settlement_confirmed',
                    $order ? $order->id : $settlement->id,
                    "COD cash settlement #{$settlement->id} confirmed by Admin for Order #" . ($order ? $order->id : $settlement->order_id),
                    [
                        'settlement_id' => $settlement->id,
                        'order_id' => $settlement->order_id,
                        'amount' => (float) $settlement->amount,
                    ]
                );
            }
        }

        return back()->with('success', "Settlement #{$settlement->id} confirmed as paid.");
    }

    /**
     * Record one income row per recipient for an approved settlement.
     * Income types: restaurant commission, tax/gst, platform share,
     * restaurant payout and delivery partner payout.
     */
    protected function recordIncomes(CodSettlement $settlement, array $payout): void
    {
        $order = $settlement->order;
        if (!$order) {
            return;
        }

        Income::where('order_id', $order->id)->delete();

        $taxPct = (float) CompanySetting::firstSetting()->taxGstPercentage();

        $entries = [
            [
                'income_type' => Income::TYPE_RESTAURANT_COMMISSION,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'restaurant_id' => $order->restaurant_id,
                'base_amount' => $payout['food_total'],
                'percentage' => $payout['commission_pct'],
                'amount' => $payout['commission'],
            ],
            [
                'income_type' => Income::TYPE_TAX,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'restaurant_id' => $order->restaurant_id,
                'base_amount' => $payout['food_total'],
                'percentage' => $taxPct,
                'amount' => $payout['tax'],
            ],
            [
                'income_type' => Income::TYPE_PLATFORM_SHARE,
                'recipient_type' => Income::RECIPIENT_PLATFORM,
                'recipient_id' => null,
                'user_id' => null,
                'restaurant_id' => $order->restaurant_id,
                'base_amount' => $payout['delivery_charge'],
                'percentage' => $payout['platform_share_pct'],
                'amount' => round($payout['delivery_charge'] * $payout['platform_share_pct'] / 100, 2),
            ],
            [
                'income_type' => Income::TYPE_RESTAURANT_PAYOUT,
                'recipient_type' => Income::RECIPIENT_RESTAURANT,
                'recipient_id' => $order->restaurant?->user_id,
                'user_id' => $order->restaurant?->user_id,
                'restaurant_id' => $order->restaurant_id,
                'base_amount' => $payout['food_total'],
                'percentage' => $payout['commission_pct'],
                'amount' => $payout['restaurant_payout'],
            ],
            [
                'income_type' => Income::TYPE_DELIVERY_PARTNER_PAYOUT,
                'recipient_type' => Income::RECIPIENT_DELIVERY_PARTNER,
                'recipient_id' => $settlement->delivery_partner_id,
                'user_id' => $settlement->delivery_partner_id,
                'restaurant_id' => $order->restaurant_id,
                'base_amount' => $payout['delivery_charge'],
                'percentage' => $payout['partner_share_pct'],
                'amount' => $payout['delivery_partner_payout'],
            ],
        ];

        foreach ($entries as $entry) {
            Income::create($entry + [
                'order_id' => $order->id,
                'paid_at' => now(),
            ]);
        }
    }

    /**
     * Reject a pending COD settlement. Partner can resubmit with a new remark.
     */
    public function reject(Request $request, $id): RedirectResponse
    {
        $settlement = CodSettlement::findOrFail($id);

        if (!$settlement->isPending()) {
            return back()->with('error', 'This settlement has already been reviewed.');
        }

        $data = $request->validate([
            'remark' => ['required', 'string', 'max:500'],
        ]);

        $settlement->update([
            'status' => CodSettlement::STATUS_REJECTED,
            'remark' => $data['remark'],
            'reviewed_at' => now(),
            'reviewed_by' => auth('admin')->id(),
        ]);

        AuditLog::log('COD Settlement', 'rejected', null, $settlement->fresh()->toArray(),
            "COD settlement #{$settlement->id} for order #{$settlement->order_id} rejected.");

        return back()->with('success', "Settlement #{$settlement->id} rejected. The delivery partner can resubmit.");
    }
}
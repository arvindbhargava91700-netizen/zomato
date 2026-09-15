<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the delivery partner's wallet transactions.
     */
    public function index(Request $request): View
    {
        $partner = auth()->user();
        $wallet = $partner->getOrCreateWallet();

        $query = WalletTransaction::where('wallet_id', $wallet->id);

        // Filter: Type (credit / debit)
        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        // Filter: Search Keyword (transaction number or description)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%");
            });
        }

        // Filter: Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $allPartnerTransactions = WalletTransaction::where('wallet_id', $wallet->id)->get();

        $stats = [
            'wallet_balance' => (float) $wallet->balance,
            'total_credits'  => (float) $allPartnerTransactions->where('type', WalletTransaction::TYPE_CREDIT)->sum('amount'),
            'total_debits'   => (float) $allPartnerTransactions->where('type', WalletTransaction::TYPE_DEBIT)->sum('amount'),
            'total_count'    => $allPartnerTransactions->count(),
        ];

        $transactions = $query->latest()->paginate(15)->withQueryString();
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.delivery-partner.transactions.index', compact(
            'transactions',
            'wallet',
            'stats',
            'setting',
            'currencySymbol'
        ));
    }

    /**
     * Display single transaction details.
     */
    public function show(WalletTransaction $transaction): View
    {
        $partner = auth()->user();

        // Ensure the transaction belongs to the current partner's wallet
        if ($transaction->user_id !== $partner->id) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $transaction->load(['wallet', 'user']);
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        // If reference is an order, load order details
        $order = null;
        if ($transaction->reference_id && in_array($transaction->source, ['delivery_partner_payout', 'cod_cash_collected', 'cod_settlement_confirmed'])) {
            $order = \App\Models\Order::with(['restaurant', 'user', 'address'])->find($transaction->reference_id);
        }

        return view('manage.delivery-partner.transactions.show', compact('transaction', 'setting', 'order', 'currencySymbol'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Order;
use App\Models\Role;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletTransactionController extends Controller
{
    /**
     * Display a listing of all system wallet transactions with filters & metrics.
     */
    public function index(Request $request): View
    {
        $query = WalletTransaction::with(['wallet', 'user.role']);

        // Filter: Search Keyword (Txn #, Description, Source, or User name/email/phone)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter: Transaction Type (credit / debit)
        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        // Filter: User Role (Delivery Partner, Restaurant, Customer, etc.)
        if ($request->filled('role_id')) {
            $roleId = (int) $request->role_id;
            $query->whereHas('user', function ($userQuery) use ($roleId) {
                $userQuery->where('role_id', $roleId);
            });
        }

        // Filter: Source Category
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter: Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // System Metrics & KPIs
        $totalSystemWalletsBalance = (float) Wallet::sum('balance');
        $allTxns = WalletTransaction::all();
        $totalSystemCredits = (float) $allTxns->where('type', WalletTransaction::TYPE_CREDIT)->sum('amount');
        $totalSystemDebits = (float) $allTxns->where('type', WalletTransaction::TYPE_DEBIT)->sum('amount');
        $totalTxnCount = $allTxns->count();

        // Paginated list
        $transactions = $query->latest()->paginate(10)->withQueryString();

        $roles = Role::orderBy('name')->get();
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        // Unique sources for filter dropdown
        $availableSources = WalletTransaction::distinct()->pluck('source')->filter()->values();

        return view('manage.admin.wallet-transactions.index', compact(
            'transactions',
            'roles',
            'setting',
            'currencySymbol',
            'totalSystemWalletsBalance',
            'totalSystemCredits',
            'totalSystemDebits',
            'totalTxnCount',
            'availableSources'
        ));
    }

    /**
     * Display single wallet transaction voucher / slip details.
     */
    public function show(WalletTransaction $transaction): View
    {
        $transaction->load(['wallet', 'user.role']);

        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        // Load linked order if reference exists
        $order = null;
        if ($transaction->reference_id && in_array($transaction->source, ['delivery_partner_payout', 'cod_cash_collected', 'cod_settlement_confirmed', 'order_payment', 'order_restaurant_payout'])) {
            $order = Order::with(['restaurant', 'user', 'address', 'items'])->find($transaction->reference_id);
        }

        return view('manage.admin.wallet-transactions.show', compact(
            'transaction',
            'setting',
            'order',
            'currencySymbol'
        ));
    }
}

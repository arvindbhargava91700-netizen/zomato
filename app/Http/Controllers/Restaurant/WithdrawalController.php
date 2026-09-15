<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Restaurant;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the restaurant owner's withdrawal requests & wallet balance.
     */
    public function index(): View
    {
        $owner = auth()->user();
        $wallet = $owner->getOrCreateWallet();
        $restaurant = Restaurant::where('user_id', $owner->id)->first();

        $withdrawals = Withdrawal::where('user_id', $owner->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'wallet_balance'     => (float) $wallet->balance,
            'total_withdrawn'    => (float) Withdrawal::where('user_id', $owner->id)->where('status', Withdrawal::STATUS_APPROVED)->sum('amount'),
            'pending_withdrawal' => (float) Withdrawal::where('user_id', $owner->id)->where('status', Withdrawal::STATUS_PENDING)->sum('amount'),
            'rejected_withdrawal'=> (float) Withdrawal::where('user_id', $owner->id)->where('status', Withdrawal::STATUS_REJECTED)->sum('amount'),
        ];

        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.restaurant.withdrawals.index', compact(
            'owner',
            'wallet',
            'restaurant',
            'withdrawals',
            'stats',
            'setting',
            'currencySymbol'
        ));
    }

    /**
     * Show dedicated page to create a new restaurant payout request.
     */
    public function create(): View
    {
        $owner = auth()->user();
        $wallet = $owner->getOrCreateWallet();
        $restaurant = Restaurant::where('user_id', $owner->id)->first();
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.restaurant.withdrawals.create', compact(
            'owner',
            'wallet',
            'restaurant',
            'setting',
            'currencySymbol'
        ));
    }

    /**
     * Submit a new withdrawal payout request.
     */
    public function store(Request $request): RedirectResponse
    {
        $owner = auth()->user();
        $wallet = $owner->getOrCreateWallet();
        $restaurant = Restaurant::where('user_id', $owner->id)->first();
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        $request->validate([
            'amount'         => 'required|numeric|min:10',
            'payout_method'  => 'required|in:bank_transfer,upi',
            'bank_name'      => 'required_if:payout_method,bank_transfer|nullable|string|max:100',
            'account_number' => 'required_if:payout_method,bank_transfer|nullable|string|max:50',
            'ifsc_code'      => 'required_if:payout_method,bank_transfer|nullable|string|max:20',
            'holder_name'    => 'required_if:payout_method,bank_transfer|nullable|string|max:100',
            'upi_id'         => 'required_if:payout_method,upi|nullable|string|max:100',
            'notes'          => 'nullable|string|max:255',
        ]);

        $amount = round((float) $request->amount, 2);

        if ($wallet->balance < $amount) {
            return back()->with('error', "Insufficient wallet balance. You have {$currencySymbol}" . number_format($wallet->balance, 2) . " available.");
        }

        $accountDetails = [
            'payout_method'  => $request->payout_method,
            'holder_name'    => $request->holder_name ?: ($restaurant?->restaurant_name ?? $owner->name),
            'bank_name'      => $request->bank_name ?: $restaurant?->bank_name,
            'account_number' => $request->account_number ?: $restaurant?->account_number,
            'ifsc_code'      => $request->ifsc_code ?: $restaurant?->ifsc_code,
            'upi_id'         => $request->upi_id,
            'pan_number'     => $restaurant?->pan_number,
            'restaurant_name'=> $restaurant?->restaurant_name,
            'phone'          => $owner->phone,
        ];

        DB::transaction(function () use ($owner, $wallet, $restaurant, $amount, $request, $accountDetails) {
            $withdrawal = Withdrawal::create([
                'withdrawal_number' => Withdrawal::generateWithdrawalNumber('WD-RES'),
                'user_id'           => $owner->id,
                'wallet_id'         => $wallet->id,
                'restaurant_id'     => $restaurant?->id,
                'amount'            => $amount,
                'fee'               => 0.00,
                'net_amount'        => $amount,
                'payout_method'     => $request->payout_method,
                'account_details'   => $accountDetails,
                'status'            => Withdrawal::STATUS_PENDING,
                'notes'             => $request->notes,
                'requested_at'      => now(),
            ]);

            // Immediately debit the requested amount from the wallet to prevent double-spending
            $wallet->debit(
                $amount,
                'withdrawal_request',
                $withdrawal->id,
                "Withdrawal Request #{$withdrawal->withdrawal_number} (Pending Admin Approval)",
                [
                    'withdrawal_id' => $withdrawal->id,
                    'restaurant_id' => $restaurant?->id,
                    'payout_method' => $request->payout_method,
                ]
            );
        });

        return redirect()->route('restaurant.withdrawals.index')
            ->with('success', "Withdrawal request of {$currencySymbol}" . number_format($amount, 2) . " submitted successfully! Admin will review and process your payout.");
    }
}

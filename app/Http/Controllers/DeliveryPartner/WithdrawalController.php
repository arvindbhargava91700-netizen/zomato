<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the delivery partner's withdrawal requests & balance.
     */
    public function index(): View
    {
        $partner = auth()->user();
        $wallet = $partner->getOrCreateWallet();

        $withdrawals = Withdrawal::where('user_id', $partner->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'wallet_balance'     => (float) $wallet->balance,
            'total_withdrawn'    => (float) Withdrawal::where('user_id', $partner->id)->where('status', Withdrawal::STATUS_APPROVED)->sum('amount'),
            'pending_withdrawal' => (float) Withdrawal::where('user_id', $partner->id)->where('status', Withdrawal::STATUS_PENDING)->sum('amount'),
            'rejected_withdrawal'=> (float) Withdrawal::where('user_id', $partner->id)->where('status', Withdrawal::STATUS_REJECTED)->sum('amount'),
        ];

        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.delivery-partner.withdrawals.index', compact(
            'partner',
            'wallet',
            'withdrawals',
            'stats',
            'setting',
            'currencySymbol'
        ));
    }

    /**
     * Show dedicated page to create a new withdrawal request.
     */
    public function create(): View
    {
        $partner = auth()->user();
        $wallet = $partner->getOrCreateWallet();
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.delivery-partner.withdrawals.create', compact(
            'partner',
            'wallet',
            'setting',
            'currencySymbol'
        ));
    }

    /**
     * Submit a new withdrawal payout request.
     */
    public function store(Request $request): RedirectResponse
    {
        $partner = auth()->user();
        $wallet = $partner->getOrCreateWallet();
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
            'holder_name'    => $request->holder_name ?: $partner->name,
            'bank_name'      => $request->bank_name ?: $partner->bank_name,
            'account_number' => $request->account_number ?: $partner->bank_account,
            'ifsc_code'      => $request->ifsc_code ?: $partner->ifsc_code,
            'upi_id'         => $request->upi_id,
            'pan_card'       => $partner->pan_card,
            'phone'          => $partner->phone,
        ];

        DB::transaction(function () use ($partner, $wallet, $amount, $request, $accountDetails) {
            $withdrawal = Withdrawal::create([
                'withdrawal_number' => Withdrawal::generateWithdrawalNumber('WD-DP'),
                'user_id'           => $partner->id,
                'wallet_id'         => $wallet->id,
                'amount'            => $amount,
                'fee'               => 0.00,
                'net_amount'        => $amount,
                'payout_method'     => $request->payout_method,
                'account_details'   => $accountDetails,
                'status'            => Withdrawal::STATUS_PENDING,
                'notes'             => $request->notes,
                'requested_at'      => now(),
            ]);

            // Immediately debit the requested amount from the wallet so it cannot be double-spent
            $wallet->debit(
                $amount,
                'withdrawal_request',
                $withdrawal->id,
                "Withdrawal Request #{$withdrawal->withdrawal_number} (Pending Admin Approval)",
                [
                    'withdrawal_id' => $withdrawal->id,
                    'payout_method' => $request->payout_method,
                ]
            );
        });

        return redirect()->route('delivery-partner.withdrawals.index')
            ->with('success', "Withdrawal request of {$currencySymbol}" . number_format($amount, 2) . " submitted successfully! Admin will review and process your payout.");
    }
}

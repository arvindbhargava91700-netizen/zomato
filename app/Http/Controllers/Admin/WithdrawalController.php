<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Role;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of all system withdrawal payout requests.
     */
    public function index(Request $request): View
    {
        $query = Withdrawal::with(['user.role', 'restaurant', 'wallet', 'admin']);

        // Filter: Status (pending / approved / rejected)
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter: Search (Withdrawal #, User name, phone, email, or restaurant name)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('withdrawal_number', 'like', "%{$search}%")
                    ->orWhere('admin_transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('restaurant', function ($resQ) use ($search) {
                        $resQ->where('restaurant_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter: User Role
        if ($request->filled('role_id')) {
            $roleId = (int) $request->role_id;
            $query->whereHas('user', function ($userQ) use ($roleId) {
                $userQ->where('role_id', $roleId);
            });
        }

        // Filter: Payout Method
        if ($request->filled('payout_method')) {
            $query->where('payout_method', $request->payout_method);
        }

        // Filter: Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('requested_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('requested_at', '<=', $request->date_to);
        }

        // Stats calculation
        $allWithdrawals = Withdrawal::all();
        $stats = [
            'total_pending_count'   => $allWithdrawals->where('status', Withdrawal::STATUS_PENDING)->count(),
            'total_pending_amount'  => (float) $allWithdrawals->where('status', Withdrawal::STATUS_PENDING)->sum('amount'),
            'total_approved_amount' => (float) $allWithdrawals->where('status', Withdrawal::STATUS_APPROVED)->sum('amount'),
            'total_rejected_amount' => (float) $allWithdrawals->where('status', Withdrawal::STATUS_REJECTED)->sum('amount'),
            'total_requests_count'  => $allWithdrawals->count(),
        ];

        $withdrawals = $query->latest('requested_at')->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.admin.withdrawals.index', compact(
            'withdrawals',
            'stats',
            'roles',
            'setting',
            'currencySymbol'
        ));
    }

    /**
     * Display single withdrawal request details.
     */
    public function show(Withdrawal $withdrawal): View
    {
        $withdrawal->load(['user.role', 'restaurant', 'wallet', 'admin']);
        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        return view('manage.admin.withdrawals.show', compact('withdrawal', 'setting', 'currencySymbol'));
    }

    /**
     * Approve and mark a withdrawal request as settled.
     */
    public function approve(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== Withdrawal::STATUS_PENDING) {
            return back()->with('error', 'This withdrawal request has already been processed.');
        }

        $request->validate([
            'admin_transaction_id' => 'required|string|max:100',
            'admin_remarks'        => 'nullable|string|max:255',
        ]);

        $withdrawal->update([
            'status'               => Withdrawal::STATUS_APPROVED,
            'admin_transaction_id' => $request->admin_transaction_id,
            'admin_remarks'        => $request->admin_remarks,
            'admin_id'             => auth('admin')->id(),
            'processed_at'         => now(),
        ]);

        return back()->with('success', "Withdrawal request #{$withdrawal->withdrawal_number} approved successfully!");
    }

    /**
     * Reject a withdrawal request and auto-refund the balance back to the user's wallet.
     */
    public function reject(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== Withdrawal::STATUS_PENDING) {
            return back()->with('error', 'This withdrawal request has already been processed.');
        }

        $request->validate([
            'admin_remarks' => 'required|string|max:255',
        ]);

        $setting = CompanySetting::firstSetting();
        $currencySymbol = $setting ? $setting->currencySymbol() : '₹';

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status'        => Withdrawal::STATUS_REJECTED,
                'admin_remarks' => $request->admin_remarks,
                'admin_id'      => auth('admin')->id(),
                'processed_at'  => now(),
            ]);

            // Auto-Refund the held amount back to user's wallet
            $user = $withdrawal->user;
            if ($user) {
                $user->getOrCreateWallet()->credit(
                    $withdrawal->amount,
                    'withdrawal_refund',
                    $withdrawal->id,
                    "Refund: Withdrawal #{$withdrawal->withdrawal_number} rejected by Admin (Reason: {$request->admin_remarks})",
                    [
                        'withdrawal_id' => $withdrawal->id,
                        'rejected_reason' => $request->admin_remarks,
                    ]
                );
            }
        });

        return back()->with('success', "Withdrawal #{$withdrawal->withdrawal_number} rejected. Amount of {$currencySymbol}" . number_format($withdrawal->amount, 2) . " has been refunded back to user's wallet.");
    }
}

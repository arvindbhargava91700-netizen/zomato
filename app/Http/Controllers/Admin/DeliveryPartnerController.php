<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeliveryPartnerController extends Controller
{
    /**
     * Display a listing of delivery partners with KYC status filtering.
     */
    public function index(Request $request): View
    {
        $query = User::with('role')->deliveryPartners();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        $partners = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => User::deliveryPartners()->count(),
            'pending' => User::deliveryPartners()->kycStatus('pending')->count(),
            'approved' => User::deliveryPartners()->kycStatus('approved')->count(),
            'rejected' => User::deliveryPartners()->kycStatus('rejected')->count(),
            'not_submitted' => User::deliveryPartners()->kycStatus('not_submitted')->count(),
        ];

        return view('manage.admin.delivery-partners.index', compact('partners', 'counts'));
    }

    /**
     * Display the specified delivery partner's KYC details.
     */
    public function show(User $partner): View
    {
        if (! $partner->role || $partner->role->slug !== 'delivery_partner') {
            abort(404);
        }

        return view('manage.admin.delivery-partners.show', compact('partner'));
    }

    /**
     * Approve the delivery partner's KYC and activate the account.
     */
    public function approve(Request $request, User $partner): RedirectResponse
    {
        if (! $partner->role || $partner->role->slug !== 'delivery_partner') {
            abort(404);
        }

        $partner->update([
            'kyc_status' => 'approved',
            'status' => 'active',
            'kyc_rejected_reason' => null,
            'kyc_remark' => $request->input('kyc_remark'),
            'kyc_reviewed_at' => now(),
        ]);

        AuditLog::log(
            module: 'Delivery Partner KYC',
            action: 'APPROVE',
            newData: ['email' => $partner->email],
            description: "Delivery partner {$partner->name} KYC approved and account activated.",
            userId: $partner->id,
            userName: $partner->name
        );

        return redirect()->route('admin.delivery-partners.show', $partner->id)
            ->with('success', "KYC approved. {$partner->name}'s account is now active.");
    }

    /**
     * Reject the delivery partner's KYC.
     */
    public function reject(Request $request, User $partner): RedirectResponse
    {
        if (! $partner->role || $partner->role->slug !== 'delivery_partner') {
            abort(404);
        }

        $data = $request->validate([
            'kyc_rejected_reason' => ['required', 'string', 'max:1000'],
        ]);

        $partner->update([
            'kyc_status' => 'rejected',
            'status' => 'inactive',
            'kyc_rejected_reason' => $data['kyc_rejected_reason'],
            'kyc_reviewed_at' => now(),
        ]);

        AuditLog::log(
            module: 'Delivery Partner KYC',
            action: 'REJECT',
            newData: ['email' => $partner->email, 'reason' => $data['kyc_rejected_reason']],
            description: "Delivery partner {$partner->name} KYC rejected.",
            userId: $partner->id,
            userName: $partner->name
        );

        return redirect()->route('admin.delivery-partners.show', $partner->id)
            ->with('success', "KYC rejected for {$partner->name}. The partner has been notified.");
    }
}

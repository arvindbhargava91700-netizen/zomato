<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DiningOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiningOfferController extends Controller
{
    public function index(Request $request): View
    {
        $query = DiningOffer::with('restaurant');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('coupon_code', 'like', "%{$search}%")
                  ->orWhereHas('restaurant', function ($q2) use ($search) {
                      $q2->where('restaurant_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $offers = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => DiningOffer::count(),
            'pending' => DiningOffer::where('approval_status', 'pending')->count(),
            'approved' => DiningOffer::where('approval_status', 'approved')->count(),
            'rejected' => DiningOffer::where('approval_status', 'rejected')->count(),
        ];

        return view('manage.admin.dining-offers.index', compact('offers', 'counts'));
    }

    public function show(DiningOffer $diningOffer): View
    {
        $diningOffer->load(['restaurant', 'approver']);

        return view('manage.admin.dining-offers.show', compact('diningOffer'));
    }

    public function approve(Request $request, DiningOffer $diningOffer): RedirectResponse
    {
        $diningOffer->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->guard('admin')->id(),
            'admin_remarks' => $request->input('admin_remarks') ?: null,
        ]);

        AuditLog::log(
            'Dining Offer',
            'approved',
            null,
            ['title' => $diningOffer->title, 'restaurant_id' => $diningOffer->restaurant_id, 'coupon_code' => $diningOffer->coupon_code],
            "Dining offer '{$diningOffer->title}' approved by admin."
        );

        return redirect()->route('admin.dining-offers.show', $diningOffer->id)
            ->with('success', "Dining offer '{$diningOffer->title}' has been APPROVED and is now live for customers.");
    }

    public function reject(Request $request, DiningOffer $diningOffer): RedirectResponse
    {
        $data = $request->validate([
            'admin_remarks' => ['required', 'string', 'max:1000'],
        ]);

        $diningOffer->update([
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth()->guard('admin')->id(),
            'admin_remarks' => $data['admin_remarks'],
        ]);

        AuditLog::log(
            'Dining Offer',
            'rejected',
            null,
            ['title' => $diningOffer->title, 'restaurant_id' => $diningOffer->restaurant_id, 'reason' => $data['admin_remarks']],
            "Dining offer '{$diningOffer->title}' rejected with reason: {$data['admin_remarks']}"
        );

        return redirect()->route('admin.dining-offers.show', $diningOffer->id)
            ->with('success', "Dining offer '{$diningOffer->title}' has been REJECTED. Rejection reason recorded.");
    }
}
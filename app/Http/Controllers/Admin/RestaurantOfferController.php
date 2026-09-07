<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\RestaurantOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantOfferController extends Controller
{
    public function index(Request $request): View
    {
        $query = RestaurantOffer::with('restaurant');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $offers = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => RestaurantOffer::count(),
            'pending' => RestaurantOffer::where('approval_status', 'pending')->count(),
            'approved' => RestaurantOffer::where('approval_status', 'approved')->count(),
            'rejected' => RestaurantOffer::where('approval_status', 'rejected')->count(),
        ];

        return view('manage.admin.restaurant-offers.index', compact('offers', 'counts'));
    }

    public function show(RestaurantOffer $restaurantOffer): View
    {
        $restaurantOffer->load('restaurant');

        return view('manage.admin.restaurant-offers.show', compact('restaurantOffer'));
    }

    public function approve(Request $request, RestaurantOffer $restaurantOffer): RedirectResponse
    {
        $restaurantOffer->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'admin_remarks' => $request->input('admin_remarks'),
        ]);

        AuditLog::log(
            module: 'Restaurant Offer',
            action: 'APPROVE',
            newData: ['title' => $restaurantOffer->title, 'restaurant_id' => $restaurantOffer->restaurant_id],
            description: "Restaurant offer '{$restaurantOffer->title}' approved.",
            userId: $restaurantOffer->restaurant_id,
            userName: $restaurantOffer->restaurant?->restaurant_name
        );

        return redirect()->route('admin.restaurant-offers.show', $restaurantOffer->id)
            ->with('success', 'Offer approved and is now eligible for the public "Today\'s Deal" section.');
    }

    public function reject(Request $request, RestaurantOffer $restaurantOffer): RedirectResponse
    {
        $data = $request->validate([
            'admin_remarks' => ['required', 'string', 'max:1000'],
        ]);

        $restaurantOffer->update([
            'approval_status' => 'rejected',
            'approved_at' => null,
            'admin_remarks' => $data['admin_remarks'],
        ]);

        AuditLog::log(
            module: 'Restaurant Offer',
            action: 'REJECT',
            newData: ['title' => $restaurantOffer->title, 'restaurant_id' => $restaurantOffer->restaurant_id, 'reason' => $data['admin_remarks']],
            description: "Restaurant offer '{$restaurantOffer->title}' rejected.",
            userId: $restaurantOffer->restaurant_id,
            userName: $restaurantOffer->restaurant?->restaurant_name
        );

        return redirect()->route('admin.restaurant-offers.show', $restaurantOffer->id)
            ->with('success', 'Offer rejected.');
    }
}

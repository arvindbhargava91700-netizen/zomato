<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\DiningOffer;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DiningOfferController extends Controller
{
    /**
     * Get owner's assigned restaurant model or abort.
     */
    private function getAssignedRestaurant(): Restaurant
    {
        $owner = auth()->user();
        $restaurant = Restaurant::where('user_id', $owner?->id)->first();

        if (!$restaurant) {
            abort(403, 'No restaurant assigned to your account. Please contact administrator.');
        }

        return $restaurant;
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'cover_charge' => ['nullable', 'numeric', 'min:0'],
            'min_bill_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = DiningOffer::where('restaurant_id', $restaurant->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('coupon_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $offers = $query->latest()->paginate(10)->withQueryString();

        return view('manage.restaurant.dining-offers.index', compact('offers', 'restaurant'));
    }

    public function create(): View
    {
        $restaurant = $this->getAssignedRestaurant();

        return view('manage.restaurant.dining-offers.create', compact('restaurant'));
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $data = $request->validate($this->rules());
        $data['restaurant_id'] = $restaurant->id;
        $data['coupon_code'] = !empty($data['coupon_code']) ? strtoupper(trim($data['coupon_code'])) : null;
        $data['approval_status'] = 'pending';

        DiningOffer::create($data);

        return redirect()->route('restaurant.dining-offers.index')
            ->with('success', 'Dining offer created successfully and submitted for admin approval.');
    }

    public function edit(DiningOffer $diningOffer): View
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($diningOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        return view('manage.restaurant.dining-offers.edit', compact('diningOffer', 'restaurant'));
    }

    public function update(Request $request, DiningOffer $diningOffer): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($diningOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        $data = $request->validate($this->rules());
        $data['coupon_code'] = !empty($data['coupon_code']) ? strtoupper(trim($data['coupon_code'])) : null;

        // If previously rejected, resubmitting sets it back to pending
        if ($diningOffer->approval_status === 'rejected') {
            $data['approval_status'] = 'pending';
            $data['admin_remarks'] = null;
        }

        $diningOffer->update($data);

        $msg = $diningOffer->wasChanged('approval_status')
            ? 'Dining offer updated and resubmitted for admin review.'
            : 'Dining offer updated successfully.';

        return redirect()->route('restaurant.dining-offers.index')
            ->with('success', $msg);
    }

    public function destroy(DiningOffer $diningOffer): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($diningOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        $diningOffer->delete();

        return redirect()->route('restaurant.dining-offers.index')
            ->with('success', 'Dining offer deleted.');
    }

    public function toggleStatus(DiningOffer $diningOffer): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($diningOffer->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this offer.');
        }

        $diningOffer->update([
            'status' => $diningOffer->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Dining offer status updated.');
    }
}

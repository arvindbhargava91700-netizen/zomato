<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Get owner's assigned restaurant model or abort.
     */
    private function getAssignedRestaurant(): Restaurant
    {
        $restaurant = Restaurant::where('user_id', auth()->id())->first();

        if (!$restaurant) {
            abort(403, 'No restaurant assigned to your account. Please contact administrator.');
        }

        return $restaurant;
    }

    /**
     * Ensure the given order belongs to the owner's restaurant.
     */
    private function authorizeOrder(Order $order): void
    {
        if ((int) $order->restaurant_id !== (int) $this->getAssignedRestaurant()->id) {
            abort(403, 'Unauthorized access to this order.');
        }
    }

    /**
     * Display listing of orders for owner's restaurant.
     */
    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $status = $request->query('status');

        $orders = Order::with(['user', 'items', 'deliveryPartner'])
            ->where('restaurant_id', $restaurant->id)
            ->when($status && in_array($status, Order::STATUSES, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('manage.restaurant.orders.index', compact('restaurant', 'orders', 'status'));
    }

    /**
     * Display order details for owner's restaurant.
     */
    public function show(Order $order): View
    {
        $this->authorizeOrder($order);

        $order->load(['user', 'address', 'items', 'deliveryPartner', 'review', 'deliveryRequests.deliveryPartner']);

        $deliveryPartners = $this->availablePartners();

        return view('manage.restaurant.orders.show', compact('order', 'deliveryPartners'));
    }

    /**
     * Assign a delivery partner to a ready order.
     */
    public function assignPartner(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== Order::STATUS_READY || $order->delivery_partner_id !== null) {
            return back()->with('error', 'This order is not ready for partner assignment.');
        }

        $data = $request->validate([
            'delivery_partner_id' => ['required', 'exists:users,id'],
        ]);

        $partner = User::find($data['delivery_partner_id']);

        if (! $partner || ! $this->isDeliveryPartner($partner)) {
            return back()->with('error', 'Selected user is not a delivery partner.');
        }

        $order->update([
            'delivery_partner_id' => $partner->id,
            'status' => Order::STATUS_ASSIGNED,
            'assigned_at' => now(),
        ]);

        // Cancel any other pending requests for this order
        $order->deliveryRequests()
            ->where('status', \App\Models\DeliveryRequest::STATUS_PENDING)
            ->update(['status' => \App\Models\DeliveryRequest::STATUS_EXPIRED, 'responded_at' => now()]);

        return back()->with('success', "Delivery Partner '{$partner->name}' assigned to Order #{$order->id}.");
    }

    /**
     * (Re)send delivery requests to available delivery partners.
     */
    public function sendRequest(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== Order::STATUS_READY || $order->delivery_partner_id !== null) {
            return back()->with('error', 'This order is not ready for a delivery request.');
        }

        Order::expireStaleRequests();

        $sent = $order->sendDeliveryRequests();

        if ($sent > 0) {
            return back()->with('success', "Delivery request sent to {$sent} available partner(s).");
        }

        return back()->with('error', 'No available delivery partners right now. Try again later.');
    }

    /**
     * Users that can take deliveries (role slug: delivery_partner).
     */
    private function availablePartners()
    {
        return User::whereHas('role', function ($q) {
            $q->where('slug', 'delivery_partner');
        })->orderBy('name')->get();
    }

    /**
     * Is the given user a delivery partner?
     */
    private function isDeliveryPartner(User $user): bool
    {
        return $user->role && $user->role->slug === 'delivery_partner';
    }

    /**
     * Accept a pending order.
     */
    public function accept(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'This order can no longer be accepted.');
        }

        $order->update([
            'status' => Order::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);

        return back()->with('success', "Order #{$order->id} accepted successfully.");
    }

    /**
     * Reject a pending order.
     */
    public function reject(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'This order can no longer be rejected.');
        }

        $order->update([
            'status' => Order::STATUS_REJECTED,
            'rejected_at' => now(),
            'cancel_reason' => 'Restaurant rejected your order.',
        ]);

        return back()->with('success', "Order #{$order->id} rejected.");
    }

    /**
     * Start preparing an accepted order.
     */
    public function preparing(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== Order::STATUS_ACCEPTED) {
            return back()->with('error', 'Order must be accepted before preparing.');
        }

        $order->update([
            'status' => Order::STATUS_PREPARING,
            'preparing_at' => now(),
        ]);

        return back()->with('success', "Order #{$order->id} is now being prepared.");
    }

    /**
     * Mark an order as ready for pickup/delivery.
     */
    public function ready(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== Order::STATUS_PREPARING) {
            return back()->with('error', 'Order must be preparing before marking ready.');
        }

        $order->update([
            'status' => Order::STATUS_READY,
            'ready_at' => now(),
        ]);

        $sent = $order->sendDeliveryRequests();

        $message = "Order #{$order->id} is ready.";
        $message .= $sent > 0 ? " Delivery request sent to {$sent} partner(s)." : ' No available delivery partners right now.';

        return back()->with('success', $message);
    }
}
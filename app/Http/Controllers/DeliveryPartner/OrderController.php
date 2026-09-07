<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CodSettlement;
use App\Models\DeliveryRequest;
use App\Models\Order;
use App\Notifications\CodSettlementSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Delivery requests sent to the current partner that are still pending.
     */
    public function available(): View
    {
        Order::expireStaleRequests();

        $requests = DeliveryRequest::with(['order.restaurant', 'order.user', 'order.items'])
            ->where('delivery_partner_id', auth()->id())
            ->where('status', DeliveryRequest::STATUS_PENDING)
            ->latest()
            ->paginate(10);

        return view('manage.delivery-partner.orders.available', compact('requests'));
    }

    /**
     * Orders assigned to the current partner.
     */
    public function deliveries(): View
    {
        $orders = Order::with(['restaurant', 'user', 'items', 'latestCodSettlement'])
            ->where('delivery_partner_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('manage.delivery-partner.orders.deliveries', compact('orders'));
    }

    /**
     * Display delivery order details.
     */
    public function show(Order $order): View
    {
        if ($order->delivery_partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this delivery.');
        }

        $order->load(['restaurant', 'user', 'address', 'items', 'review', 'latestCodSettlement']);

        $setting = \App\Models\CompanySetting::firstSetting();

        return view('manage.delivery-partner.orders.show', compact('order', 'setting'));
    }

    /**
     * Accept a delivery request and assign the order to the current partner.
     */
    public function acceptRequest(DeliveryRequest $request): RedirectResponse
    {
        $this->authorizeRequest($request);

        if (!$request->isPending() || $request->isExpired()) {
            return back()->with('error', 'This delivery request is no longer available.');
        }

        $order = $request->order;

        if ($order->status !== Order::STATUS_READY || $order->delivery_partner_id !== null) {
            $request->update(['status' => DeliveryRequest::STATUS_EXPIRED, 'responded_at' => now()]);
            return back()->with('error', 'This order has already been assigned to someone else.');
        }

        $request->update([
            'status' => DeliveryRequest::STATUS_ACCEPTED,
            'responded_at' => now(),
        ]);

        $order->update([
            'delivery_partner_id' => auth()->id(),
            'status' => Order::STATUS_ASSIGNED,
            'assigned_at' => now(),
        ]);

        // Let other partners know the order is taken
        $order->deliveryRequests()
            ->where('id', '!=', $request->id)
            ->where('status', DeliveryRequest::STATUS_PENDING)
            ->update(['status' => DeliveryRequest::STATUS_EXPIRED, 'responded_at' => now()]);

        return redirect()->route('delivery-partner.orders.show', $order->id)
            ->with('success', "Delivery #{$order->id} accepted. Head to the restaurant for pickup.");
    }

    /**
     * Reject a delivery request. The order then tries the next partner.
     */
    public function rejectRequest(DeliveryRequest $request): RedirectResponse
    {
        $this->authorizeRequest($request);

        if ($request->isPending()) {
            $request->update([
                'status' => DeliveryRequest::STATUS_REJECTED,
                'responded_at' => now(),
            ]);
        }

        // Try the next available partner if the order is still waiting
        $order = $request->order;
        if ($order->status === Order::STATUS_READY && $order->delivery_partner_id === null) {
            $sent = $order->sendDeliveryRequests();
            if ($sent === 0) {
                Order::expireStaleRequests();
                $order->sendDeliveryRequests();
            }
        }

        return back()->with('success', 'Request rejected. Another partner will be notified.');
    }

    /**
     * Mark order as picked up from the restaurant.
     */
    public function pickedUp(Order $order): RedirectResponse
    {
        $this->authorizePartner($order);

        if ($order->status !== Order::STATUS_ASSIGNED) {
            return back()->with('error', 'Order must be assigned before pickup.');
        }

        $order->update([
            'status' => Order::STATUS_PICKED_UP,
            'picked_up_at' => now(),
        ]);

        return back()->with('success', 'Order collected. Start delivery now.');
    }

    /**
     * Mark order as out for delivery.
     */
    public function outForDelivery(Order $order): RedirectResponse
    {
        $this->authorizePartner($order);

        if ($order->status !== Order::STATUS_PICKED_UP) {
            return back()->with('error', 'Order must be picked up first.');
        }

        $order->update([
            'status' => Order::STATUS_OUT_FOR_DELIVERY,
            'out_for_delivery_at' => now(),
        ]);

        return back()->with('success', 'Order is out for delivery.');
    }

    /**
     * Mark order as delivered.
     */
    public function delivered(Order $order): RedirectResponse
    {
        $this->authorizePartner($order);

        if ($order->status !== Order::STATUS_OUT_FOR_DELIVERY) {
            return back()->with('error', 'Order must be out for delivery first.');
        }

        $order->update([
            'status' => Order::STATUS_DELIVERED,
            'delivered_at' => now(),
            'payment_status' => 'paid',
        ]);

        // Process financial distributions (COD cash debit, partner delivery payout, restaurant food payout, admin commission)
        $order->distributeDeliveryFinancials();

        return back()->with('success', 'Order delivered successfully. Financial earnings and wallet balances updated.');
    }

    /**
     * Reject an assigned delivery before pickup. The order returns to
     * "ready" so the restaurant can request another partner.
     */
    public function rejectOrder(Order $order): RedirectResponse
    {
        $this->authorizePartner($order);

        if (!in_array($order->status, [Order::STATUS_ASSIGNED, Order::STATUS_PICKED_UP])) {
            return back()->with('error', 'This delivery can no longer be rejected.');
        }

        // Record the rejection on this partner's request (if one exists)
        $request = $order->deliveryRequests()
            ->where('delivery_partner_id', auth()->id())
            ->latest()
            ->first();

        if ($request) {
            $request->update([
                'status' => DeliveryRequest::STATUS_REJECTED,
                'responded_at' => now(),
            ]);
        } else {
            $order->deliveryRequests()->create([
                'delivery_partner_id' => auth()->id(),
                'status' => DeliveryRequest::STATUS_REJECTED,
                'responded_at' => now(),
            ]);
        }

        // Unassign and return the order to "ready" for another partner
        $order->update([
            'delivery_partner_id' => null,
            'status' => Order::STATUS_READY,
            'assigned_at' => null,
            'picked_up_at' => null,
        ]);

        // Try the next available partner
        $sent = $order->sendDeliveryRequests();
        if ($sent === 0) {
            Order::expireStaleRequests();
            $order->sendDeliveryRequests();
        }

        return redirect()->route('delivery-partner.orders.deliveries')
            ->with('success', "Delivery #{$order->id} rejected. Another partner will be notified.");
    }

    /**
     * Ensure the request belongs to the current partner.
     */
    private function authorizeRequest(DeliveryRequest $request): void
    {
        if ($request->delivery_partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this delivery request.');
        }
    }

    /**
     * Ensure the order is assigned to the current partner.
     */
    private function authorizePartner(Order $order): void
    {
        if ($order->delivery_partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this delivery.');
        }
    }
}
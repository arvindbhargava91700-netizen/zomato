<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private function getAssignedRestaurant(): ?Restaurant
    {
        return Restaurant::where('user_id', auth()->id())->first();
    }

    public function index(Request $request)
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = Booking::with('diningOffer')
            ->where('restaurant_id', $restaurant?->id)
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        $bookings = $query->paginate(15)->withQueryString();

        $statuses = [
            Booking::STATUS_PENDING,
            Booking::STATUS_ACCEPTED,
            Booking::STATUS_REJECTED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
        ];

        return view('manage.restaurant.bookings.index', compact('bookings', 'statuses', 'restaurant'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($booking->restaurant_id !== $restaurant?->id) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', [
                Booking::STATUS_PENDING,
                Booking::STATUS_ACCEPTED,
                Booking::STATUS_REJECTED,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
            ])],
        ]);

        $booking->update($data);

        return redirect()->route('restaurant.bookings.index')
            ->with('success', 'Booking status updated successfully.');
    }

    public function bill(Request $request, Booking $booking)
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($booking->restaurant_id !== $restaurant?->id) {
            abort(403);
        }

        if (! in_array($booking->status, [Booking::STATUS_ACCEPTED, Booking::STATUS_COMPLETED])) {
            return redirect()->route('restaurant.bookings.index')
                ->with('error', 'Bill can only be viewed for confirmed bookings.');
        }

        $foods = collect();
        $categories = collect();
        if ($restaurant) {
            $foods = $restaurant->foods()
                ->with(['variants', 'category'])
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $categories = $restaurant->categories()
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get();
        }

        return view('manage.restaurant.bookings.bill', compact('booking', 'restaurant', 'foods', 'categories'));
    }

    public function billStore(Request $request, Booking $booking)
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($booking->restaurant_id !== $restaurant?->id) {
            abort(403);
        }

        if (! in_array($booking->status, [Booking::STATUS_ACCEPTED, Booking::STATUS_COMPLETED])) {
            return redirect()->route('restaurant.bookings.index')
                ->with('error', 'Bill can only be generated for confirmed bookings.');
        }

        $data = $request->validate([
            'bill_items' => ['required', 'array', 'min:1'],
            'bill_items.*.name' => ['required', 'string', 'max:150'],
            'bill_items.*.price' => ['required', 'numeric', 'min:0'],
            'bill_items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $subtotal = 0;
        foreach ($data['bill_items'] as $item) {
            $subtotal += (float) $item['price'] * (int) $item['qty'];
        }

        $booking->update([
            'bill_items' => $data['bill_items'],
            'food_bill' => $subtotal,
            'bill_status' => Booking::BILL_STATUS_PENDING,
            'bill_paid_at' => null,
        ]);

        return redirect()->route('restaurant.bookings.bill.show', $booking->id)
            ->with('success', 'Bill saved successfully. You can now print the bill.');
    }

    public function markAsPaid(Request $request, Booking $booking)
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($booking->restaurant_id !== $restaurant?->id) {
            abort(403);
        }

        if (! $booking->bill_items || empty($booking->bill_items)) {
            return redirect()->route('restaurant.bookings.bill.show', $booking->id)
                ->with('error', 'Save the bill items before marking it as paid.');
        }

        $booking->update([
            'bill_status' => Booking::BILL_STATUS_PAID,
            'bill_paid_at' => now(),
            'status' => Booking::STATUS_COMPLETED,
        ]);

        return redirect()->route('restaurant.bookings.bill.show', $booking->id)
            ->with('success', 'Bill marked as paid and booking completed successfully.');
    }

    public function deleteBill(Request $request, Booking $booking)
    {
        $restaurant = $this->getAssignedRestaurant();

        if ($booking->restaurant_id !== $restaurant?->id) {
            abort(403);
        }

        $booking->update([
            'bill_items' => null,
            'food_bill' => null,
            'bill_status' => Booking::BILL_STATUS_PENDING,
            'bill_paid_at' => null,
        ]);

        return redirect()->route('restaurant.bookings.bill.show', $booking->id)
            ->with('success', 'Bill deleted. You can now create a new bill for this booking.');
    }
}

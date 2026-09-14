<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DiningSetupController extends Controller
{
    private function getAssignedRestaurant(): ?Restaurant
    {
        return Restaurant::where('user_id', auth()->id())->first();
    }

    /**
     * Show Dining Setup & Tables dashboard.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            return redirect()->route('restaurant.dashboard')
                ->with('error', 'No restaurant profile found.');
        }

        // All tables for metrics (unfiltered)
        $allTables = $restaurant->tables()->orderByRaw('LENGTH(table_number), table_number')->get();
        $totalTables = $allTables->count();
        $availableTablesCount = $allTables->where('status', RestaurantTable::STATUS_AVAILABLE)->count();
        $maintenanceTablesCount = $allTables->where('status', RestaurantTable::STATUS_MAINTENANCE)->count();
        $inactiveTablesCount = $allTables->where('status', RestaurantTable::STATUS_INACTIVE)->count();
        $totalSeatingCapacity = $allTables->where('status', RestaurantTable::STATUS_AVAILABLE)->sum('capacity');

        // Filtered + Paginated table list
        $tablesQuery = $restaurant->tables();

        if ($request->filled('search')) {
            $tablesQuery->where('table_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && in_array($request->status, [RestaurantTable::STATUS_AVAILABLE, RestaurantTable::STATUS_MAINTENANCE, RestaurantTable::STATUS_INACTIVE], true)) {
            $tablesQuery->where('status', $request->status);
        }

        $tables = $tablesQuery->orderByRaw('LENGTH(table_number), table_number')->paginate(10)->withQueryString();

        // Target Date for Slot Inspector
        $selectedDate = $request->query('date', Carbon::today()->toDateString());
        $selectedDateObj = Carbon::parse($selectedDate);

        // Generate Time Slots
        $slots = $restaurant->generateTimeSlots($selectedDate);

        // Calculate availability for each slot on selected date
        $slotMatrix = [];
        foreach ($slots as $slot) {
            $availability = $restaurant->getSlotAvailability($selectedDate, $slot['time']);
            $slotMatrix[] = array_merge($slot, $availability);
        }

        // Active bookings for the selected date
        $dateBookings = Booking::with('table')
            ->where('restaurant_id', $restaurant->id)
            ->where('book_date', $selectedDate)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_ACCEPTED])
            ->latest()
            ->get();

        $currencySymbol = config('app.currency_symbol', '₹');

        return view('manage.restaurant.dining-setup.index', compact(
            'restaurant',
            'tables',
            'totalTables',
            'availableTablesCount',
            'maintenanceTablesCount',
            'inactiveTablesCount',
            'totalSeatingCapacity',
            'selectedDate',
            'selectedDateObj',
            'slotMatrix',
            'dateBookings',
            'currencySymbol'
        ));
    }

    /**
     * Update Dining Configuration (Hours, Slot Duration, Advance Days).
     */
    public function updateSettings(Request $request): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            abort(403);
        }

        $validated = $request->validate([
            'opening_time' => ['required'],
            'closing_time' => ['required'],
            'slot_duration_minutes' => ['required', 'integer', 'min:15', 'max:240'],
            'advance_booking_days' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $restaurant->update([
            'opening_time' => $validated['opening_time'],
            'closing_time' => $validated['closing_time'],
            'slot_duration_minutes' => (int) $validated['slot_duration_minutes'],
            'advance_booking_days' => (int) $validated['advance_booking_days'],
        ]);

        $message = 'Dining hours and slot configuration updated successfully!';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'slots' => $restaurant->generateTimeSlots(),
            ]);
        }

        return redirect()->route('restaurant.dining-setup.index')
            ->with('success', $message);
    }

    /**
     * Show page to create a single table.
     */
    public function createTable(): View|RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            return redirect()->route('restaurant.dashboard')
                ->with('error', 'No restaurant profile found.');
        }

        return view('manage.restaurant.dining-setup.create', compact('restaurant'));
    }

    /**
     * Show page to batch generate tables.
     */
    public function batchCreatePage(): View|RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            return redirect()->route('restaurant.dashboard')
                ->with('error', 'No restaurant profile found.');
        }

        return view('manage.restaurant.dining-setup.batch', compact('restaurant'));
    }

    /**
     * Show page to edit an existing table.
     */
    public function editTable(RestaurantTable $table): View|RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        return view('manage.restaurant.dining-setup.edit', compact('restaurant', 'table'));
    }

    /**
     * Store a new table.
     */
    public function storeTable(Request $request): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            abort(403);
        }

        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables')->where(function ($query) use ($restaurant) {
                    return $query->where('restaurant_id', $restaurant->id);
                }),
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:available,inactive,maintenance'],
        ]);

        $table = RestaurantTable::create([
            'restaurant_id' => $restaurant->id,
            'table_number' => strtoupper(trim($validated['table_number'])),
            'capacity' => (int) $validated['capacity'],
            'status' => $validated['status'],
        ]);

        // Sync count
        $restaurant->update(['total_tables_count' => $restaurant->tables()->count()]);

        $message = "Table '{$table->table_number}' with capacity {$table->capacity} created successfully!";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'table' => $table,
            ]);
        }

        return redirect()->route('restaurant.dining-setup.index')
            ->with('success', $message);
    }


    /**
     * Update an existing table.
     */
    public function updateTable(Request $request, RestaurantTable $table): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables')->where(function ($query) use ($restaurant) {
                    return $query->where('restaurant_id', $restaurant->id);
                })->ignore($table->id),
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:available,inactive,maintenance'],
        ]);

        $table->update([
            'table_number' => strtoupper(trim($validated['table_number'])),
            'capacity' => (int) $validated['capacity'],
            'status' => $validated['status'],
        ]);

        $message = "Table '{$table->table_number}' updated successfully!";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'table' => $table,
            ]);
        }

        return redirect()->route('restaurant.dining-setup.index', ['#tables'])
            ->with('success', $message);
    }

    /**
     * Delete a table.
     */
    public function destroyTable(RestaurantTable $table): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $tableName = $table->table_number;
        $table->delete();

        // Sync count
        $restaurant->update(['total_tables_count' => $restaurant->tables()->count()]);

        $message = "Table '{$tableName}' deleted successfully.";

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('restaurant.dining-setup.index', ['#tables'])
            ->with('success', $message);
    }

    /**
     * Toggle table status (available <-> maintenance/inactive).
     */
    public function toggleTableStatus(RestaurantTable $table): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant || $table->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $newStatus = $table->status === RestaurantTable::STATUS_AVAILABLE
            ? RestaurantTable::STATUS_MAINTENANCE
            : RestaurantTable::STATUS_AVAILABLE;

        $table->update(['status' => $newStatus]);

        $message = "Table '{$table->table_number}' status changed to " . ucfirst($newStatus) . ".";

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $newStatus,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Batch / Quick Add multiple tables (e.g. T1 to T6).
     */
    public function batchCreateTables(Request $request): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            abort(403);
        }

        $validated = $request->validate([
            'prefix' => ['required', 'string', 'max:10'],
            'start_num' => ['required', 'integer', 'min:1', 'max:500'],
            'count' => ['required', 'integer', 'min:1', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:available,inactive,maintenance'],
        ]);

        $created = 0;
        $skipped = 0;

        for ($i = 0; $i < $validated['count']; $i++) {
            $num = $validated['start_num'] + $i;
            $tableNum = strtoupper(trim($validated['prefix'])) . $num;

            $exists = RestaurantTable::where('restaurant_id', $restaurant->id)
                ->where('table_number', $tableNum)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            RestaurantTable::create([
                'restaurant_id' => $restaurant->id,
                'table_number' => $tableNum,
                'capacity' => (int) $validated['capacity'],
                'status' => $validated['status'],
            ]);

            $created++;
        }

        // Sync count
        $restaurant->update(['total_tables_count' => $restaurant->tables()->count()]);

        $message = "Successfully created {$created} tables." . ($skipped > 0 ? " ({$skipped} tables skipped as duplicates)" : '');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'created' => $created,
                'skipped' => $skipped,
            ]);
        }

        return redirect()->route('restaurant.dining-setup.index', ['#tables'])
            ->with('success', $message);
    }

    /**
     * Get live slot availability API for a given date.
     */
    public function getSlotAvailabilityApi(Request $request): JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        if (!$restaurant) {
            return response()->json(['success' => false, 'message' => 'Restaurant not found.'], 404);
        }

        $date = $request->query('date', Carbon::today()->toDateString());
        $guests = (int) $request->query('guests', 1);

        $slots = $restaurant->generateTimeSlots($date);
        $matrix = [];

        foreach ($slots as $slot) {
            $availability = $restaurant->getSlotAvailability($date, $slot['time'], $guests);
            $matrix[] = array_merge($slot, $availability);
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'guests' => $guests,
            'slot_duration' => $restaurant->slot_duration_minutes,
            'slots' => $matrix,
        ]);
    }
}

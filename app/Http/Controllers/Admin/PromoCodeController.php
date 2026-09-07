<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PromoCodeController extends Controller
{
    /**
     * List promo codes with campaign summary and filters.
     */
    public function index(Request $request): View
    {
        $query = PromoCode::with('assignedUser');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('campaign_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('campaign_name')) {
            $query->where('campaign_name', $request->campaign_name);
        }

        if ($request->filled('usage_status')) {
            $query->where('usage_status', $request->usage_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $codes = $query->latest()->paginate(20)->withQueryString();

        // Campaign summary
        $campaigns = PromoCode::select('campaign_name', 'discount_type', 'discount_value', 'code_type')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN usage_status = 'used' THEN 1 ELSE 0 END) as used")
            ->selectRaw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_codes")
            ->groupBy('campaign_name', 'discount_type', 'discount_value', 'code_type')
            ->orderBy('campaign_name')
            ->get();

        $stats = [
            'total' => PromoCode::count(),
            'used' => PromoCode::where('usage_status', 'used')->count(),
            'unused' => PromoCode::where('usage_status', 'unused')->count(),
            'campaigns' => $campaigns->count(),
        ];

        $campaignNames = PromoCode::distinct()->orderBy('campaign_name')->pluck('campaign_name');

        return view('manage.admin.promo-codes.index', compact('codes', 'campaigns', 'campaignNames', 'stats'));
    }

    /**
     * Show the campaign creation form.
     */
    public function create(): View
    {
        return view('manage.admin.promo-codes.create');
    }

    /**
     * Store the campaign and auto-generate unique codes.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'campaign_name' => ['required', 'string', 'max:120'],
            'code_type' => ['nullable', 'string', 'max:60'],
            'discount_type' => ['required', 'in:percentage,flat'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'maximum_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'total_codes' => ['required', 'integer', 'min:1', 'max:5000'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'status' => ['nullable', 'in:active,inactive'],
        ], [
            'total_codes.max' => 'You can generate a maximum of 5000 codes at once.',
        ]);

        $prefix = $this->makePrefix($data['campaign_name']);
        $total = (int) $data['total_codes'];
        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $max = strlen($charset) - 1;

        $now = now();
        $rows = [];
        $usedCodes = [];

        for ($i = 0; $i < $total; $i++) {
            do {
                $suffix = '';
                for ($j = 0; $j < 4; $j++) {
                    $suffix .= $charset[random_int(0, $max)];
                }
                $code = $prefix . '-' . $suffix;
            } while (isset($usedCodes[$code]) || PromoCode::where('code', $code)->exists());

            $usedCodes[$code] = true;

            $rows[] = [
                'campaign_name' => $data['campaign_name'],
                'code' => $code,
                'code_type' => $data['code_type'] ?? null,
                'discount_type' => $data['discount_type'],
                'discount_value' => $data['discount_value'],
                'minimum_order_amount' => $data['minimum_order_amount'] ?? 0,
                'maximum_discount_amount' => $data['maximum_discount_amount'] ?? null,
                'per_user_limit' => $data['per_user_limit'] ?? 1,
                'assigned_user_id' => null,
                'usage_status' => 'unused',
                'used_at' => null,
                'valid_from' => $data['valid_from'] ?? null,
                'valid_until' => $data['valid_until'] ?? null,
                'status' => $data['status'] ?? 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::transaction(function () use ($rows) {
            foreach (array_chunk($rows, 200) as $chunk) {
                PromoCode::insert($chunk);
            }
        });

        return redirect()->route('admin.promo-codes.index', ['campaign_name' => $data['campaign_name']])
            ->with('success', "Campaign \"{$data['campaign_name']}\" created successfully with {$total} unique codes.");
    }

    /**
     * Show a single promo code.
     */
    public function show(PromoCode $promoCode): View
    {
        $promoCode->load('assignedUser');
        return view('manage.admin.promo-codes.show', compact('promoCode'));
    }

    /**
     * Delete a single promo code.
     */
    public function destroy(PromoCode $promoCode): RedirectResponse
    {
        $promoCode->delete();
        return back()->with('success', 'Promo code deleted successfully.');
    }

    /**
     * Toggle status of a single promo code.
     */
    public function toggleStatus(PromoCode $promoCode): RedirectResponse
    {
        $promoCode->update([
            'status' => $promoCode->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "Promo code status updated to {$promoCode->status}.");
    }

    /**
     * Delete an entire campaign (all its codes).
     */
    public function deleteCampaign(Request $request): RedirectResponse
    {
        $campaign = $request->input('campaign_name');
        if ($campaign) {
            PromoCode::where('campaign_name', $campaign)->delete();
            return redirect()->route('admin.promo-codes.index')
                ->with('success', "Campaign \"{$campaign}\" and all its codes deleted.");
        }

        return back()->with('error', 'Campaign not found.');
    }

    /**
     * Build the code prefix from the campaign name (e.g. "Welcome Offer" => WELCOME).
     */
    private function makePrefix(string $campaignName): string
    {
        $firstWord = explode(' ', trim($campaignName))[0] ?? $campaignName;
        $prefix = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $firstWord));

        return $prefix ?: 'PROMO';
    }
}

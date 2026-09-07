<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of all payment gateways in Admin.
     */
    public function index(): View
    {
        $gateways = PaymentGateway::orderBy('sort_order')->orderBy('id')->get();
        return view('manage.admin.payment-gateways.index', compact('gateways'));
    }

    /**
     * Show form to edit payment gateway credentials (e.g. Razorpay).
     */
    public function edit(string $gatewayKey): View|RedirectResponse
    {
        $gateway = PaymentGateway::where('gateway_key', $gatewayKey)->first();

        if (!$gateway) {
            return redirect()->route('admin.payment-gateways.index')
                ->with('error', 'Payment gateway not found.');
        }

        return view('manage.admin.payment-gateways.edit', compact('gateway'));
    }

    /**
     * Update payment gateway credentials and status.
     */
    public function update(Request $request, string $gatewayKey): RedirectResponse
    {
        $gateway = PaymentGateway::where('gateway_key', $gatewayKey)->firstOrFail();

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:150'],
            'mode' => ['required', 'in:sandbox,live'],
            'is_active' => ['nullable', 'boolean'],
            'key_id' => ['required', 'string', 'max:255'],
            'key_secret' => ['required', 'string', 'max:255'],
            'webhook_secret' => ['nullable', 'string', 'max:255'],
            'merchant_id' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
        ]);

        $gateway->update([
            'display_name' => $validated['display_name'],
            'mode' => $validated['mode'],
            'is_active' => $request->has('is_active'),
            'key_id' => trim($validated['key_id']),
            'key_secret' => trim($validated['key_secret']),
            'webhook_secret' => $validated['webhook_secret'] ? trim($validated['webhook_secret']) : null,
            'merchant_id' => $validated['merchant_id'] ? trim($validated['merchant_id']) : null,
            'currency' => strtoupper(trim($validated['currency'])),
            'theme_color' => $validated['theme_color'] ?? '#072654',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', "{$gateway->name} payment gateway credentials updated successfully!");
    }

    /**
     * Toggle active/inactive status of a payment gateway.
     */
    public function toggleStatus(string $gatewayKey): RedirectResponse|JsonResponse
    {
        $gateway = PaymentGateway::where('gateway_key', $gatewayKey)->firstOrFail();
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        $statusText = $gateway->is_active ? 'enabled' : 'disabled';
        $message = "{$gateway->name} gateway has been {$statusText}.";

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $gateway->is_active,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}

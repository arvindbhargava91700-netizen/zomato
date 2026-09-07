<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeliveryPartnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('delivery-partner.login')->with('error', 'Please login to access your delivery partner panel.');
        }

        $user = Auth::guard('admin')->user();

        if ($user->role !== 'delivery_partner') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('delivery-partner.login')->with('error', 'Unauthorized access. Only Delivery Partners can access this area.');
        }

        if ($user->status !== 'active') {
            // Inactive partner: allow dashboard access but show KYC required message
            // Don't logout - let them see the dashboard with KYC completion steps
            $request->session()->put('kyc_pending', true);
            $request->session()->put('kyc_status', 'inactive');
            return $next($request);
        }

        // Active partner: full access
        $request->session()->put('kyc_pending', false);
        return $next($request);
    }
}
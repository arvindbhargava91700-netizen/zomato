<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestaurantOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('restaurant.login')->with('error', 'Please login to access your restaurant panel.');
        }

        $user = Auth::guard('admin')->user();

        if ($user->role !== 'restaurant_owner') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('restaurant.login')->with('error', 'Unauthorized access. Only Restaurant Owners can access this area.');
        }

        if ($user->status !== 'active') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('restaurant.login')->with('error', 'Your restaurant owner account is inactive. Please contact support.');
        }

        return $next($request);
    }
}

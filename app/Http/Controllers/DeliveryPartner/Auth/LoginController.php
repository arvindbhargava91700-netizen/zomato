<?php

namespace App\Http\Controllers\DeliveryPartner\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show Delivery Partner login view.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'delivery_partner') {
            return redirect()->route('delivery-partner.dashboard');
        }

        return view('manage.delivery-partner.auth.login');
    }

    /**
     * Handle Delivery Partner login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();

            if ($user->role !== 'delivery_partner') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Access denied. Account is not registered as a Delivery Partner.']);
            }

            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Your delivery partner account is inactive. Please contact system administrator.']);
            }

            // Update last login timestamp
            $user->update(['last_login' => now()]);
            $request->session()->regenerate();

            // Record login in audit log
            AuditLog::log(
                module: 'Delivery Partner Auth',
                action: 'LOGIN',
                newData: ['email' => $user->email],
                description: "Delivery partner {$user->name} logged in successfully.",
                userId: $user->id,
                userName: $user->name
            );

            return redirect()->intended(route('delivery-partner.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the delivery partner out.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();

        AuditLog::log(
            module: 'Delivery Partner Auth',
            action: 'LOGOUT',
            description: "Delivery partner {$user->name} logged out.",
            userId: $user?->id,
            userName: $user?->name
        );

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('delivery-partner.login')->with('success', 'Logged out successfully.');
    }
}
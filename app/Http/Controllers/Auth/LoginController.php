<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BlockedIp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the application login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function login(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $ipAddress = $request->ip();
    $blockedIp = BlockedIp::firstOrCreate(['ip_address' => $ipAddress]);

    if ($blockedIp->is_blocked) {
        if ($blockedIp->blocked_at && $blockedIp->blocked_at->copy()->addHours(24)->isPast()) {
            $blockedIp->update([
                'is_blocked' => false,
                'failed_attempts' => 0,
                'blocked_at' => null,
            ]);
        } else {
            return back()->withErrors([
                'email' => 'Too many failed login attempts. Please try again after 24 hours.',
            ]);
        }
    }

    if (!Auth::attempt($credentials)) {
        $blockedIp->increment('failed_attempts');
        
        $blockedIp->update([
            'details' => [
                'last_email' => $request->email,
                'user_agent' => $request->userAgent()
            ]
        ]);
        
        if ($blockedIp->failed_attempts >= 5) {
            $blockedIp->update([
                'is_blocked' => true,
                'blocked_at' => now(),
            ]);
            return back()->withErrors([
                'email' => 'Too many failed login attempts. Please try again after 24 hours.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    if ($blockedIp->failed_attempts > 0) {
        $blockedIp->update(['failed_attempts' => 0]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    AuditLog::log(
        module: 'Auth',
        action: 'LOGIN',
        newData: ['email' => $user->email],
        description: "User {$user->name} logged in successfully.",
        userId: $user->id,
        userName: $user->name
    );

    if ($user->role->slug === 'restaurant_owner') {
        return redirect()->route('restaurant.dashboard');
    }

    if ($user->role->slug === 'delivery_partner') {
        return redirect()->route('delivery-partner.dashboard');
    }

    if ($user->role->slug === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role->slug === 'customer') {
        return redirect()->route('index');
    }

    return redirect()->route('home');
}

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
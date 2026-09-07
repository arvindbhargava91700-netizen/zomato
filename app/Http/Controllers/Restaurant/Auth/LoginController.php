<?php

namespace App\Http\Controllers\Restaurant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show Restaurant Owner login view.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'restaurant_owner') {
            return redirect()->route('restaurant.dashboard');
        }

        return view('manage.restaurant.auth.login');
    }

    /**
     * Handle Restaurant Owner login request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $user = Auth::guard('admin')->user();

            if ($user->role !== 'restaurant_owner') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Access denied. Account is not registered as a Restaurant Owner.']);
            }

            if ($user->status !== 'active') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Your restaurant owner account is inactive. Please contact system administrator.']);
            }

            // Update last login timestamp
            $user->update(['last_login' => now()]);
            $request->session()->regenerate();

            // Record login in audit log
            AuditLog::log(
                module: 'Restaurant Auth',
                action: 'LOGIN',
                newData: ['email' => $user->email],
                description: "Restaurant owner {$user->name} logged in successfully.",
                userId: $user->id,
                userName: $user->name
            );

            return redirect()->intended(route('restaurant.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the restaurant owner out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('restaurant.login')->with('success', 'Logged out successfully.');
    }
}

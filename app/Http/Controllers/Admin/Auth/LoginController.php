<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show admin login view.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('manage.admin.auth.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $admin = Auth::guard('admin')->user();

            if ($admin->status !== 'active') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
            }

            // Update last login timestamp
            $admin->update(['last_login' => now()]);
            $request->session()->regenerate();

            // Record login in audit log
            AuditLog::log(
                module: 'Admin Auth',
                action: 'LOGIN',
                newData: ['email' => $admin->email, 'role' => $admin->role],
                description: "Admin {$admin->name} logged in successfully.",
                userId: $admin->id,
                userName: $admin->name
            );

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back, ' . $admin->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        AuditLog::log(
            module: 'Admin Auth',
            action: 'LOGOUT',
            description: "Admin {$admin->name} logged out.",
            userId: $admin?->id,
            userName: $admin?->name
        );

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}

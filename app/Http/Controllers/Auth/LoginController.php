<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
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

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
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
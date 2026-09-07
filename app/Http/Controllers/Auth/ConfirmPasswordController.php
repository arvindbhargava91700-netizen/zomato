<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConfirmPasswordController extends Controller
{
    /**
     * Show the password confirmation view.
     */
    public function showConfirmForm()
    {
        return view('auth.passwords.confirm');
    }

    /**
     * Confirm the given user's password.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => __('auth.password')]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
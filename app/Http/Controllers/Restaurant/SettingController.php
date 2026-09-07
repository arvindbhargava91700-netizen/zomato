<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the account settings form.
     */
    public function accountSettings(): View
    {
        $setting = CompanySetting::firstSetting();

        return view('manage.restaurant.settings.account', compact('setting'));
    }

    /**
     * Update the account settings.
     */
    public function accountSettingsUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'new_password' => 'required|string|min:8|same:password_confirmation',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $owner = auth()->user();

        if (!Hash::check($request->password, $owner->password)) {
            return back()->withErrors(['password' => 'The current password is incorrect.']);
        }

        $owner->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Account settings updated successfully.');
    }
}
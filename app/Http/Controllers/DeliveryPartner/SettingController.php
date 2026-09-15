<?php

namespace App\Http\Controllers\DeliveryPartner;

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
        $user = auth()->user();
        $setting = CompanySetting::firstSetting();

        return view('manage.delivery-partner.settings.account', compact('user', 'setting'));
    }

    /**
     * Update the account settings.
     */
    public function accountSettingsUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'password' => 'required|string',
            'new_password' => 'required|string|min:8|same:password_confirmation',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The current password is incorrect.']);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'ifsc_code' => $request->ifsc_code,
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Account settings updated successfully.');
    }
}
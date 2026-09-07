<?php

namespace App\Http\Controllers\Admin;

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
     * Show the general settings form.
     */
    public function edit(): View
    {
        $setting = CompanySetting::firstSetting();

        return view('manage.admin.settings.index', compact('setting'));
    }

    /**
     * Update the general settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $setting = CompanySetting::firstSetting();

        $data = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'timezone' => ['required', 'string', 'max:60'],
            'date_format' => ['required', 'string', 'max:30'],
            'tax_gst' => ['nullable', 'string', 'max:255'],
            'invoice_prefix' => ['nullable', 'string', 'max:20'],
            'receipt_prefix' => ['nullable', 'string', 'max:20'],
            'payment_details' => ['nullable', 'string'],
            'delivery_partner_share' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'delivery_partner_cod_limit' => ['nullable', 'numeric', 'min:0'],
            'platform_share' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'dining_com_per' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'dining_restaurant_share' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cover_charge_admin_share' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cover_charge_restaurant_share' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'logo_lg' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'logo_sm' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,ico,webp', 'max:1024'],
            'payment_qr' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        foreach (['logo_lg', 'logo_sm', 'favicon', 'payment_qr'] as $field) {
            if ($request->hasFile($field)) {
                if ($setting->{$field} && File::exists(public_path($setting->{$field}))) {
                    File::delete(public_path($setting->{$field}));
                }
                $fileName = time().'_'.$field.'_'.Str::random(5).'.'.$request->file($field)->extension();
                $request->file($field)->move(public_path('uploads/settings'), $fileName);
                $data[$field] = 'uploads/settings/'.$fileName;
            }
        }

        $setting->update($data);

        return back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Show the account settings form.
     */
    public function accountSettings(): View
    {
        $setting = CompanySetting::firstSetting();

        return view('manage.admin.settings.account', compact('setting'));
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

        $admin = auth('admin')->user();

        if (! Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'The current password is incorrect.']);
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Account settings updated successfully.');
    }
}

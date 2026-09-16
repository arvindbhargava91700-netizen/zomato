<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryPartner\ChangePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $partner = Auth::user();
        return view('manage.delivery-partner.profile', compact('partner'));
    }

    /**
     * Display the Delivery Partner's full profile & uploaded KYC documents.
     */
    public function profileDetail(): View
    {
        $partner = Auth::user();
        return view('manage.delivery-partner.profile-detail', compact('partner'));
    }

    /**
     * Display the Delivery Partner's dedicated KYC page.
     */
    public function kyc(): View
    {
        $partner = Auth::user();
        return view('manage.delivery-partner.kyc', compact('partner'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $partner = Auth::user();

        $data = [
            'name' => $request->name,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($partner->profile_image && File::exists(public_path($partner->profile_image))) {
                File::delete(public_path($partner->profile_image));
            }

            $imageName = time() . '_partner_' . Str::random(5) . '.' . $request->file('profile_image')->extension();
            $request->file('profile_image')->move(public_path('uploads/partners/profiles'), $imageName);
            $data['profile_image'] = 'uploads/partners/profiles/' . $imageName;
        }

        $partner->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change Delivery Partner Password.
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $partner = Auth::user();

        if (!Hash::check($request->current_password, $partner->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match.']);
        }

        $partner->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Handle KYC document submission.
     */
    public function kycSubmit(Request $request): RedirectResponse
    {
        $partner = Auth::user();

        $request->validate([
            'aadhar_front' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'aadhar_back' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rc_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only([
            'phone',
            'age',
            'country_id',
            'state_id',
            'city_id',
            'vehicle_type',
            'vehicle_number',
            'aadhar_card',
            'pan_card',
            'bank_account',
            'ifsc_code',
            'bank_name',
        ]);

        $cityName = \App\Models\City::where('id', $request->city_id)->value('name');
        if ($cityName) {
            $data['city'] = $cityName;
        }

        $uploads = [
            'aadhar_front' => 'uploads/partners/kyc',
            'aadhar_back' => 'uploads/partners/kyc',
            'passport_photo' => 'uploads/partners/kyc',
            'rc_image' => 'uploads/partners/kyc',
        ];

        foreach ($uploads as $field => $folder) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $imageName = time() . '_' . $field . '_' . Str::random(5) . '.' . $file->extension();
                $file->move(public_path($folder), $imageName);
                $data[$field] = $folder . '/' . $imageName;
            }
        }

        // Update partner profile with KYC details
        $data['kyc_status'] = 'pending';

        $partner->update($data);

        // Keep status as inactive until admin reviews and approves the KYC.
        // The KYC request now appears in the admin delivery partner management panel.

        return back()->with('success', 'KYC details submitted successfully. Your documents have been sent for review and admin will activate your account after approval.');
    }

    /**
     * Update the live location (GPS coordinates) of the delivery partner.
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'live_lat' => ['required', 'numeric', 'between:-90,90'],
            'live_lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $partner = Auth::user();
        $partner->update([
            'live_lat' => $request->live_lat,
            'live_lng' => $request->live_lng,
        ]);

        return response()->json(['success' => true, 'message' => 'Location updated successfully']);
    }
}

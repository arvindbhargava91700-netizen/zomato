<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\ChangePasswordRequest;
use App\Http\Requests\Restaurant\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the Restaurant Owner Profile page.
     */
    public function index(): View
    {
        $owner = Auth::user();
        return view('manage.restaurant.profile', compact('owner'));
    }

    /**
     * Update Restaurant Owner Profile details (Name, Mobile, Profile Picture).
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $owner = Auth::user();

        $data = [
            'name' => $request->name,
            'mobile' => $request->mobile,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($owner->profile_image && File::exists(public_path($owner->profile_image))) {
                File::delete(public_path($owner->profile_image));
            }

            $imageName = time() . '_owner_' . Str::random(5) . '.' . $request->file('profile_image')->extension();
            $request->file('profile_image')->move(public_path('uploads/owners/profiles'), $imageName);
            $data['profile_image'] = 'uploads/owners/profiles/' . $imageName;
        }

        $owner->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change Restaurant Owner Password.
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $owner = Auth::user();

        if (!Hash::check($request->current_password, $owner->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match.']);
        }

        $owner->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}

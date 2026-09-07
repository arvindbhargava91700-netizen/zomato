<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the Admin Profile page.
     */
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();
        return view('manage.admin.profile', compact('admin'));
    }

    /**
     * Update Admin Profile details.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}

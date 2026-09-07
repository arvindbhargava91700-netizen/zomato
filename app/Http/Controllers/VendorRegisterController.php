<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class VendorRegisterController extends Controller
{
    /**
     * Show the "Become a Vendor" registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('manage.front.vendorRegister');
    }

    /**
     * Handle a restaurant vendor registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $vendor = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2,
            'status' => 'inactive',
        ]);

        AuditLog::log(
            module: 'Vendor Registration',
            action: 'REGISTER',
            newData: ['email' => $vendor->email, 'role' => $vendor->role],
            description: "Restaurant vendor {$vendor->name} registered successfully.",
            userId: $vendor->id,
            userName: $vendor->name
        );

        return back()->with('success', 'Your restaurant vendor account has been created successfully. Please login to continue.');
    }
}
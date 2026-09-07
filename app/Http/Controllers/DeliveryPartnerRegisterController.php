<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DeliveryPartnerRegisterController extends Controller
{
    /**
     * Show the "Become a Courier" registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('manage.front.deliveryPartnerRegister');
    }

    /**
     * Handle a delivery partner (courier boy) registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $partner = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 3,
            'status' => 'inactive', 
        ]);

        AuditLog::log(
            module: 'Delivery Partner Registration',
            action: 'REGISTER',
            newData: ['email' => $partner->email, 'role' => $partner->role],
            description: "Delivery partner {$partner->name} registered successfully.",
            userId: $partner->id,
            userName: $partner->name
        );

        return back()->with('success', 'Your delivery partner account has been created successfully. Please login to continue.');
    }
}
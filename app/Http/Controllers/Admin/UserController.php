<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::with('role');

        $typeLabels = [
            'customer' => 'Customer',
            'restaurant_owner' => 'Vendor',
            'delivery_partner' => 'Delivery Partner',
        ];

        $type = $request->filled('type') ? $request->type : null;
        $typeLabel = $typeLabels[$type] ?? null;

        if ($typeLabel) {
            $query->whereHas('role', fn ($q) => $q->where('slug', $type));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $roles = Role::orderBy('name')->get();

        $title = getPageTitle($typeLabel ? $typeLabel . 's' : 'Users');

        return view('manage.admin.users.index', compact('users', 'roles', 'type', 'typeLabel', 'title'));
    }

    /**
     * Show form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get();

        return view('manage.admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show form for editing specified user.
     */
    public function edit(User $user): View
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get();

        return view('manage.admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'. $user->id],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
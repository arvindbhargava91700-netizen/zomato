<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubAdminController extends Controller
{
    /**
     * Display a listing of sub-admins (admins with the 'admin' role).
     */
    public function index(Request $request): View
    {
        $query = Admin::with('assignedRole')->where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subAdmins = $query->latest()->paginate(10)->withQueryString();

        return view('manage.admin.sub-admins.index', compact('subAdmins'));
    }

    /**
     * Show the form for creating a new sub-admin.
     */
    public function create(): View
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get(['id', 'name', 'slug']);

        return view('manage.admin.sub-admins.create', compact('roles'));
    }

    /**
     * Store a newly created sub-admin in the admins table.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role_id' => ['nullable', 'string', 'exists:roles,slug'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $role =Role::where('slug', $data['role_id'])->first();
        $data['role'] = $role->name;
        $data = $this->resolveRoleSlug($data);

        Admin::create($data);

        return redirect()->route('admin.sub-admins.index')->with('success', 'Sub Admin created successfully.');
    }

    /**
     * Show the form for editing the specified sub-admin.
     */
    public function edit(Admin $subAdmin): View
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get(['id', 'name', 'slug']);

        return view('manage.admin.sub-admins.edit', compact('subAdmin', 'roles'));
    }

    /**
     * Update the specified sub-admin.
     */
    public function update(Request $request, Admin $subAdmin): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $subAdmin->id],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role_id' => ['nullable', 'string', 'exists:roles,slug'],
            'status' => ['required', 'in:active,inactive'],
        ]);

      //  dd($data);
         $role =Role::where('slug', $data['role_id'])->first();
        $data['role'] = $role->name;

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data = $this->resolveRoleSlug($data);

        $subAdmin->update($data);

        return redirect()->route('admin.sub-admins.index')->with('success', 'Sub Admin updated successfully.');
    }

    /**
     * Convert the incoming role slug into role_id (FK) + role_slug for the admins row.
     */
    protected function resolveRoleSlug(array $data): array
    {
        $slug = $data['role_id'] ?? null;

        if (! empty($slug)) {
            $role = Role::where('slug', $slug)->first();
            $data['role_id'] = $role?->id;
            $data['role_slug'] = $role?->slug;
        } else {
            $data['role_id'] = null;
            $data['role_slug'] = null;
        }

        return $data;
    }

    /**
     * Toggle the status of the specified sub-admin.
     */
    public function toggleStatus(Admin $subAdmin): RedirectResponse
    {
        $subAdmin->update(['status' => $subAdmin->status === 'active' ? 'inactive' : 'active']);

        return back()->with('success', 'Sub Admin status updated successfully.');
    }

    /**
     * Remove the specified sub-admin.
     */
    public function destroy(Admin $subAdmin): RedirectResponse
    {
        $subAdmin->delete();

        return redirect()->route('admin.sub-admins.index')->with('success', 'Sub Admin deleted successfully.');
    }
}
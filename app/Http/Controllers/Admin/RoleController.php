<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request): View
    {
        $query = Role::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $roles = $query->latest()->paginate(10)->withQueryString();

        return view('manage.admin.roles.index', compact('roles'));
    }

    /**
     * Show form for creating a new role.
     */
    public function create(): View
    {
        return view('manage.admin.roles.create');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['slug'] = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show form for editing specified role.
     */
    public function edit(Role $role): View
    {
        return view('manage.admin.roles.edit', compact('role'));
    }

    /**
     * Update specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug,'. $role->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (! empty($data['slug']) && $data['slug'] !== $role->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } else {
            unset($data['slug']);
        }

        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Delete specified role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
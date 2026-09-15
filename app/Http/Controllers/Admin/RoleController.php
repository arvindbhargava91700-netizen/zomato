<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Static permission checklist (single source of truth).
     *
     * Categories group modules; each module renders as its own card exposing
     * one permission per action (view / edit / delete), producing slugs like
     * `restaurants.view`.
     */
    public const PERMISSIONS = [
        'Dashboard' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'feather-airplay', 'actions' => ['view']],
        ],
        'Restaurants Management' => [
            ['key' => 'restaurants', 'label' => 'Restaurants', 'icon' => 'feather-shopping-bag', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'brands', 'label' => 'Brands', 'icon' => 'feather-award', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'restaurant-features', 'label' => 'Restaurant Features', 'icon' => 'feather-star', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'nightlife-banners', 'label' => 'Nightlife Banners', 'icon' => 'feather-moon', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'dining-offers', 'label' => 'Dining Offers', 'icon' => 'feather-tag', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'restaurant-offers', 'label' => "Today's Deals", 'icon' => 'feather-percent', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'restaurant-blogs', 'label' => 'Restaurant Blogs', 'icon' => 'feather-edit-3', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'food-categories', 'label' => 'Food Categories', 'icon' => 'feather-folder', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'promo-codes', 'label' => 'Promo Codes', 'icon' => 'feather-gift', 'actions' => ['view', 'edit', 'delete']],
        ],
        'Super Admin' => [
            ['key' => 'users', 'label' => 'Users', 'icon' => 'feather-users', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'company-settings', 'label' => 'Company Settings', 'icon' => 'feather-settings', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'email-config', 'label' => 'Email Configuration', 'icon' => 'feather-mail', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'sms-config', 'label' => 'SMS Configuration', 'icon' => 'feather-message-square', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'email-templates', 'label' => 'Email Templates', 'icon' => 'feather-file-text', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'roles', 'label' => 'Roles & Permissions', 'icon' => 'feather-user-check', 'actions' => ['view', 'edit', 'delete']],
        ],
        'Payments' => [
            ['key' => 'payment-gateways', 'label' => 'Payment Gateways', 'icon' => 'feather-credit-card', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'transactions', 'label' => 'Payment Transactions', 'icon' => 'feather-credit-card', 'actions' => ['view']],
            ['key' => 'wallet-transactions', 'label' => 'Wallet Transactions', 'icon' => 'feather-dollar-sign', 'actions' => ['view']],
            ['key' => 'withdrawals', 'label' => 'Withdrawals', 'icon' => 'feather-arrow-up-circle', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'earnings', 'label' => 'Earnings', 'icon' => 'feather-bar-chart-2', 'actions' => ['view']],
        ],
        'Delivery Partners' => [
            ['key' => 'delivery-partners', 'label' => 'Delivery Partners', 'icon' => 'feather-truck', 'actions' => ['view', 'edit', 'delete']],
        ],
        'Support' => [
            ['key' => 'tickets', 'label' => 'Support Tickets', 'icon' => 'feather-life-buoy', 'actions' => ['view', 'edit', 'delete']],
            ['key' => 'contact-messages', 'label' => 'Contact Inquiries', 'icon' => 'feather-mail', 'actions' => ['view', 'edit', 'delete']],
        ],
    ];

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
        $permissionStructure = $this->permissionStructure();

        return view('manage.admin.roles.create', compact('permissionStructure'));
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
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $data['slug'] = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        $role = Role::create($data);

        if (! empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show form for editing specified role.
     */
    public function edit(Role $role): View
    {
        $permissionStructure = $this->permissionStructure();
        $rolePermissions = $role->permissions->pluck('id')->all();

        return view('manage.admin.roles.edit', compact('role', 'permissionStructure', 'rolePermissions'));
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
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        if (! empty($data['slug']) && $data['slug'] !== $role->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } else {
            unset($data['slug']);
        }

        $role->update($data);

        $role->permissions()->sync($data['permissions'] ?? []);

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

    /**
     * Sync the static permission checklist into the permissions table and
     * return it as a category => modules structure (one card per module),
     * ordered exactly as declared in PERMISSIONS.
     */
    protected function permissionStructure(): Collection
    {
        $slugs = [];
        $rows = [];

        foreach (self::PERMISSIONS as $category => $modules) {
            foreach ($modules as $module) {
                foreach ($module['actions'] as $action) {
                    $slug = "{$module['key']}.{$action}";
                    $slugs[] = $slug;
                    $rows[$slug] = [
                        'name' => ucwords(str_replace(['-', '.'], [' ', ' '], $slug)),
                        'group' => $module['label'],
                        'description' => ucfirst($action) . ' access to ' . $module['label'],
                    ];
                }
            }
        }

        foreach ($rows as $slug => $row) {
            Permission::updateOrCreate(['slug' => $slug], $row);
        }

        Permission::whereNotIn('slug', $slugs)->delete();

        // The super-admin role always holds every permission (full access).
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdminRole->permissions()->sync(Permission::pluck('id')->all());
        }

        $permissionModels = Permission::whereIn('slug', $slugs)->get()->keyBy('slug');

        $structure = [];

        foreach (self::PERMISSIONS as $category => $modules) {
            $builtModules = [];

            foreach ($modules as $module) {
                $modulePermissions = collect($module['actions'])
                    ->map(fn ($action) => $permissionModels->get("{$module['key']}.{$action}"))
                    ->filter()
                    ->values();

                $builtModules[] = [
                    'key' => $module['key'],
                    'label' => $module['label'],
                    'icon' => $module['icon'],
                    'total' => $modulePermissions->count(),
                    'permissions' => $modulePermissions,
                ];
            }

            $structure[] = [
                'category' => $category,
                'modules' => $builtModules,
            ];
        }

        return collect($structure);
    }
}
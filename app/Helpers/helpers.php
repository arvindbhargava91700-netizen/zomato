<?php

if (!function_exists('getPageTitle')) {
    /**
     * Get the formatted page title with the user's role.
     *
     * @param string $pageName
     * @return string
     */
    function getPageTitle($pageName)
    {
        $roleName = 'Dashboard';

        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->isSuperAdmin()) {
                $roleName = 'Super Admin';
            } else {
                $roleName = $admin->assignedRole ? $admin->assignedRole->name : 'Admin';
            }
        } elseif (auth()->check()) {
            $user = auth()->user();
            $roleName = $user->role ? $user->role->name : 'User';
        }

        return $roleName . ' || ' . $pageName;
    }
}

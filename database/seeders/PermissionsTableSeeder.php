<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\RoleController;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Seed the permissions from the static checklist in RoleController.
     */
    public function run(): void
    {
        $slugs = [];
        $rows = [];

        foreach (RoleController::PERMISSIONS as $category => $modules) {
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
    }
}
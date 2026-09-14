<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $db = DB::connection()->getDatabaseName();
        $prefix = DB::getTablePrefix();
        $usersTable = $prefix . 'users';
        $adminsTable = $prefix . 'admins';
        $menusTable = $prefix . 'restaurant_menus';

        // Drop FKs referencing admins table (if still present)
        foreach (['created_by', 'updated_by'] as $column) {
            $constraint = "{$menusTable}_{$column}_foreign";
            $exists = $this->constraintExists($db, $menusTable, $column, $constraint);
            if (!empty($exists)) {
                DB::statement("ALTER TABLE `{$menusTable}` DROP FOREIGN KEY `{$constraint}`");
            }
        }

        // Re-create FKs referencing users table
        foreach (['created_by', 'updated_by'] as $column) {
            $constraint = "{$menusTable}_{$column}_foreign";
            $hasColumnFK = $this->constraintExists($db, $menusTable, $column);
            if (empty($hasColumnFK)) {
                DB::statement("ALTER TABLE `{$menusTable}` ADD CONSTRAINT `{$constraint}` FOREIGN KEY (`{$column}`) REFERENCES `{$usersTable}`(`id`) ON DELETE SET NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $db = DB::connection()->getDatabaseName();
        $prefix = DB::getTablePrefix();
        $adminsTable = $prefix . 'admins';
        $menusTable = $prefix . 'restaurant_menus';

        foreach (['created_by', 'updated_by'] as $column) {
            $constraint = "{$menusTable}_{$column}_foreign";
            $exists = $this->constraintExists($db, $menusTable, $column, $constraint);
            if (!empty($exists)) {
                DB::statement("ALTER TABLE `{$menusTable}` DROP FOREIGN KEY `{$constraint}`");
            }
            DB::statement("ALTER TABLE `{$menusTable}` ADD CONSTRAINT `{$constraint}` FOREIGN KEY (`{$column}`) REFERENCES `{$adminsTable}`(`id`) ON DELETE SET NULL");
        }
    }

    /**
     * Check whether a foreign key constraint exists.
     */
    private function constraintExists(string $db, string $table, string $column, ?string $constraint = null): bool
    {
        if ($constraint) {
            $rows = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? LIMIT 1",
                [$db, $table, $constraint]
            );
            return count($rows) > 0;
        }

        $rows = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL LIMIT 1",
            [$db, $table, $column]
        );
        return count($rows) > 0;
    }
};
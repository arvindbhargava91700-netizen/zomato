<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The real table is the prefixed "food_wishlists" (base name "wishlists").
        $prefix = Schema::getConnection()->getTablePrefix();
        $table = $prefix . 'wishlists';

        if (Schema::hasColumn('wishlists', 'food_id')) {
            DB::statement("ALTER TABLE {$table} MODIFY food_id BIGINT UNSIGNED NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $prefix = Schema::getConnection()->getTablePrefix();
        $table = $prefix . 'wishlists';

        if (Schema::hasColumn('wishlists', 'food_id')) {
            DB::statement("ALTER TABLE {$table} MODIFY food_id BIGINT UNSIGNED NOT NULL");
        }
    }
};

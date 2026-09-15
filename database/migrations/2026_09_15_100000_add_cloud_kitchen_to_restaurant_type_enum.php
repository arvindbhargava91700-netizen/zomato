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
        $prefix = config('database.connections.mysql.prefix', '');
        DB::statement("ALTER TABLE {$prefix}restaurants MODIFY restaurant_type ENUM('restaurant', 'brand', 'nightlife', 'cloud_kitchen') NOT NULL DEFAULT 'restaurant'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $prefix = config('database.connections.mysql.prefix', '');
        DB::statement("ALTER TABLE {$prefix}restaurants MODIFY restaurant_type ENUM('restaurant', 'brand', 'nightlife') NOT NULL DEFAULT 'restaurant'");
    }
};
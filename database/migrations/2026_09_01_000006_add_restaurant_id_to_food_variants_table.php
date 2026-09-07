<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('food_variants', 'restaurant_id')) {
            Schema::table('food_variants', function (Blueprint $table) {
                $table->foreignId('restaurant_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('restaurants')
                    ->onDelete('cascade');
            });

            // Backfill restaurant_id from parent food
            $tableName = DB::getTablePrefix() . 'food_variants';
            $foodsTable = DB::getTablePrefix() . 'foods';
            DB::statement("UPDATE `{$tableName}` v 
                           INNER JOIN `{$foodsTable}` f ON v.food_id = f.id 
                           SET v.restaurant_id = f.restaurant_id 
                           WHERE v.restaurant_id IS NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('food_variants', 'restaurant_id')) {
            Schema::table('food_variants', function (Blueprint $table) {
                $table->dropForeign(['restaurant_id']);
                $table->dropColumn('restaurant_id');
            });
        }
    }
};

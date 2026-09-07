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
        // Drop foreign key and unique index if present
        Schema::table('food_categories', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropUnique(['restaurant_id', 'slug']);
        });

        Schema::table('food_categories', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->nullable()->change();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_categories', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->nullable(false)->change();
        });
    }
};

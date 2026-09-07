<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurant_blogs', function (Blueprint $table) {
            $table->foreignId('food_category_id')
                ->nullable()
                ->after('restaurant_id')
                ->constrained('food_categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_blogs', function (Blueprint $table) {
            $table->dropForeign(['food_category_id']);
            $table->dropColumn('food_category_id');
        });
    }
};

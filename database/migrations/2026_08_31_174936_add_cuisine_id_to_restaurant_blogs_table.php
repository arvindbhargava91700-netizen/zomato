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
            $table->foreignId('cuisine_id')
                ->nullable()
                ->after('food_category_id')
                ->constrained('cuisines')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_blogs', function (Blueprint $table) {
            $table->dropForeign(['cuisine_id']);
            $table->dropColumn('cuisine_id');
        });
    }
};

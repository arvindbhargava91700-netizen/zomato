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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('pet_friendly')->default(false)->after('is_pure_veg');
            $table->boolean('outdoor_seating')->default(false)->after('pet_friendly');
            $table->boolean('serves_alcohol')->default(false)->after('outdoor_seating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['pet_friendly', 'outdoor_seating', 'serves_alcohol']);
        });
    }
};

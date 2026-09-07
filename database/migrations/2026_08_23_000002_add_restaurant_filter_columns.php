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
            $table->boolean('credit_card')->default(false)->after('serves_alcohol');
            $table->boolean('buffet')->default(false)->after('credit_card');
            $table->boolean('happy_hours')->default(false)->after('buffet');
            $table->boolean('pubs_bars')->default(false)->after('happy_hours');
            $table->boolean('fine_dining')->default(false)->after('pubs_bars');
            $table->boolean('wifi')->default(false)->after('fine_dining');
            $table->boolean('cafes')->default(false)->after('wifi');
            $table->boolean('hygiene_rated')->default(false)->after('cafes');
            $table->boolean('online_bookings')->default(false)->after('hygiene_rated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'credit_card',
                'buffet',
                'happy_hours',
                'pubs_bars',
                'fine_dining',
                'wifi',
                'cafes',
                'hygiene_rated',
                'online_bookings',
            ]);
        });
    }
};

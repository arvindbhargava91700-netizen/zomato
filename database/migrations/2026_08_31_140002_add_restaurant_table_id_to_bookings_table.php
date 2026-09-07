<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'restaurant_table_id')) {
                $table->foreignId('restaurant_table_id')->nullable()->after('dining_offer_id')->constrained('restaurant_tables')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['restaurant_table_id']);
            $table->dropColumn('restaurant_table_id');
        });
    }
};

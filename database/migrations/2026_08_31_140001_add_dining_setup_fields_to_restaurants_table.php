<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurants', 'slot_duration_minutes')) {
                $table->unsignedInteger('slot_duration_minutes')->default(60)->after('closing_time');
            }
            if (!Schema::hasColumn('restaurants', 'advance_booking_days')) {
                $table->unsignedInteger('advance_booking_days')->default(7)->after('slot_duration_minutes');
            }
            if (!Schema::hasColumn('restaurants', 'total_tables_count')) {
                $table->unsignedInteger('total_tables_count')->default(0)->after('advance_booking_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['slot_duration_minutes', 'advance_booking_days', 'total_tables_count']);
        });
    }
};

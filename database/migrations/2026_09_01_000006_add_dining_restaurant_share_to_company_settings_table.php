<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('company_settings', 'dining_restaurant_share')) {
                $table->decimal('dining_restaurant_share', 5, 2)->default(90.00)->after('dining_com_per');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            if (Schema::hasColumn('company_settings', 'dining_restaurant_share')) {
                $table->dropColumn('dining_restaurant_share');
            }
        });
    }
};

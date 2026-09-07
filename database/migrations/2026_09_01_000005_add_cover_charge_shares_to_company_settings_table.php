<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('company_settings', 'cover_charge_admin_share')) {
                $table->decimal('cover_charge_admin_share', 5, 2)->default(20.00)->after('dining_com_per');
            }
            if (!Schema::hasColumn('company_settings', 'cover_charge_restaurant_share')) {
                $table->decimal('cover_charge_restaurant_share', 5, 2)->default(80.00)->after('cover_charge_admin_share');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('company_settings', 'cover_charge_admin_share')) {
                $columns[] = 'cover_charge_admin_share';
            }
            if (Schema::hasColumn('company_settings', 'cover_charge_restaurant_share')) {
                $columns[] = 'cover_charge_restaurant_share';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

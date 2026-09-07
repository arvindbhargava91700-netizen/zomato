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
        Schema::table('company_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('company_settings', 'delivery_partner_cod_limit')) {
                $table->decimal('delivery_partner_cod_limit', 10, 2)->default(5000.00)->nullable()->after('delivery_partner_share');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            if (Schema::hasColumn('company_settings', 'delivery_partner_cod_limit')) {
                $table->dropColumn('delivery_partner_cod_limit');
            }
        });
    }
};

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
        Schema::table('cod_settlements', function (Blueprint $table) {
            $table->decimal('restaurant_payout', 10, 2)->default(0)->after('amount');
            $table->decimal('delivery_partner_payout', 10, 2)->default(0)->after('restaurant_payout');
            $table->decimal('platform_payout', 10, 2)->default(0)->after('delivery_partner_payout');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cod_settlements', function (Blueprint $table) {
            $table->dropColumn(['restaurant_payout', 'delivery_partner_payout', 'platform_payout']);
        });
    }
};
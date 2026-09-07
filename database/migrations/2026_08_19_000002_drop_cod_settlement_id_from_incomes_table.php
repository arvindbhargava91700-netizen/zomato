<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove cod_settlement_id from incomes as incomes are now linked
     * to orders and users instead of the settlement record.
     */
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropIndex(['cod_settlement_id']);
            $table->dropColumn('cod_settlement_id');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('cod_settlement_id')->nullable()->index();
        });
    }
};
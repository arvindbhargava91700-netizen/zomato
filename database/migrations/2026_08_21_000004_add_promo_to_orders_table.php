<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('promo_code_id')->nullable()->after('discount');
            $table->string('promo_code')->nullable()->after('promo_code_id');
            $table->decimal('promo_discount', 10, 2)->default(0)->after('promo_code');

            $table->foreign('promo_code_id')->references('id')->on('promo_codes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['promo_code_id']);
            $table->dropColumn(['promo_code_id', 'promo_code', 'promo_discount']);
        });
    }
};

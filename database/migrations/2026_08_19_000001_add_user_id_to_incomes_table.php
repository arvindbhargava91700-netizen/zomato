<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add user_id to incomes so every income / salary row can be
     * traced back to the user it belongs to.
     */
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('recipient_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
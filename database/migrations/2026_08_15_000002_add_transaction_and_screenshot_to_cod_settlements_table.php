<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cod_settlements', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('amount');
            $table->string('screenshot_path')->nullable()->after('transaction_id');
        });
    }

    public function down()
    {
        Schema::table('cod_settlements', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'screenshot_path']);
        });
    }
};

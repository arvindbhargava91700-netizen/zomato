<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_offer')->default(true);
            $table->boolean('notify_order')->default(false);
            $table->boolean('notify_new')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_offer', 'notify_order', 'notify_new']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('delivery_charge_per_km', 10, 2)->default(20)->after('delivery_radius');
        });

        DB::table('restaurants')->update(['delivery_charge_per_km' => 20]);
    }

    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('delivery_charge_per_km');
        });
    }
};
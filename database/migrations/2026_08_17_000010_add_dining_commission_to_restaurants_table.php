<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('dining_commission_percentage', 5, 2)
                ->nullable()->after('commission_percentage')
                ->comment('Commission % charged on dining/reservation bills');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('dining_commission_percentage');
        });
    }
};

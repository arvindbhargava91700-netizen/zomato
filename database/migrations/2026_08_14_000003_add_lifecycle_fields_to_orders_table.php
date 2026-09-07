<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_partner_id')->nullable()->after('restaurant_id');
            $table->timestamp('accepted_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('accepted_at');
            $table->timestamp('preparing_at')->nullable()->after('rejected_at');
            $table->timestamp('ready_at')->nullable()->after('preparing_at');
            $table->timestamp('assigned_at')->nullable()->after('ready_at');
            $table->timestamp('picked_up_at')->nullable()->after('assigned_at');
            $table->timestamp('out_for_delivery_at')->nullable()->after('picked_up_at');
            $table->timestamp('delivered_at')->nullable()->after('out_for_delivery_at');
            $table->timestamp('completed_at')->nullable()->after('delivered_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->string('cancel_reason')->nullable()->after('cancelled_at');

            $table->foreign('delivery_partner_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_partner_id']);
            $table->dropColumn([
                'delivery_partner_id',
                'accepted_at',
                'rejected_at',
                'preparing_at',
                'ready_at',
                'assigned_at',
                'picked_up_at',
                'out_for_delivery_at',
                'delivered_at',
                'completed_at',
                'cancelled_at',
                'cancel_reason',
            ]);
        });
    }
};

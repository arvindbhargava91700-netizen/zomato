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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'user_id');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->renameColumn('user_id', 'admin_id');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->change();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }
};
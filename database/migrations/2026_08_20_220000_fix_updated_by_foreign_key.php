<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing foreign key constraint if it exists
        try {
            DB::statement("ALTER TABLE food_restaurants DROP FOREIGN KEY food_restaurants_updated_by_foreign");
        } catch (\Exception $e) {
            // Foreign key may not exist or may have different name
        }

        // Add the new foreign key constraint referencing users table
        try {
            DB::statement("ALTER TABLE food_restaurants ADD CONSTRAINT food_restaurants_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL");
        } catch (\Exception $e) {
            // Constraint may already exist or there may be other issues
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint referencing users
        try {
            DB::statement("ALTER TABLE food_restaurants DROP FOREIGN KEY food_restaurants_updated_by_foreign");
        } catch (\Exception $e) {
            // Foreign key may not exist
        }

        // Recreate the original foreign key referencing admins table
        try {
            DB::statement("ALTER TABLE food_restaurants ADD CONSTRAINT food_restaurants_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES admins(id) ON DELETE SET NULL");
        } catch (\Exception $e) {
            // Constraint may not exist or already references admins
        }
    }
};
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
        $tables = ['foods', 'food_variants', 'restaurants', 'food_categories', 'cuisines', 'brands'];

        foreach ($tables as $table) {
            $tableName = DB::getTablePrefix() . $table;
            
            $fks = DB::select("SELECT CONSTRAINT_NAME 
                               FROM information_schema.KEY_COLUMN_USAGE 
                               WHERE TABLE_SCHEMA = DATABASE() 
                               AND TABLE_NAME = ? 
                               AND (COLUMN_NAME = 'created_by' OR COLUMN_NAME = 'updated_by') 
                               AND REFERENCED_TABLE_NAME IS NOT NULL", [$tableName]);

            foreach ($fks as $fk) {
                DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};

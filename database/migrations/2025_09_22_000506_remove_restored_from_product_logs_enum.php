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
        // Check if we're using MySQL (Railway uses MySQL)
        if (DB::getDriverName() === 'mysql') {
            // Remove 'restored' from the enum for MySQL
            DB::statement("ALTER TABLE product_logs MODIFY COLUMN action ENUM('created', 'updated', 'deleted', 'force_deleted') NOT NULL");
        }
        // For SQLite, we don't need to modify the enum as it's not strictly enforced
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using MySQL
        if (DB::getDriverName() === 'mysql') {
            // Add 'restored' back if needed for MySQL
            DB::statement("ALTER TABLE product_logs MODIFY COLUMN action ENUM('created', 'updated', 'deleted', 'restored', 'force_deleted') NOT NULL");
        }
        // For SQLite, no action needed
    }
};

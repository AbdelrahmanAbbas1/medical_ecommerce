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
        // Remove 'restored' from the enum, keeping only the actions we need
        DB::statement("ALTER TABLE product_logs MODIFY COLUMN action ENUM('created', 'updated', 'deleted', 'force_deleted') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'restored' back if needed
        DB::statement("ALTER TABLE product_logs MODIFY COLUMN action ENUM('created', 'updated', 'deleted', 'restored', 'force_deleted') NOT NULL");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Orders Table
 * 
 * This migration creates the orders table for the medical ecommerce system.
 * The orders table stores customer order information including customer details
 * and the total order amount. Individual order items are stored in a separate
 * order_items table for normalization.
 * 
 * Table Structure:
 * - id: Primary key (auto-increment)
 * - customer_name: Customer's full name (required)
 * - customer_phone: Customer's phone number (required)
 * - customer_address: Customer's delivery address (required)
 * - total_price: Total order amount with 2 decimal places (required)
 * - created_at: Order creation timestamp
 * - updated_at: Order last update timestamp
 * 
 * Features:
 * - Customer information storage
 * - Decimal precision for accurate pricing
 * - Text field for flexible address storage
 * - Relationship with order_items table
 * 
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the orders table with all necessary columns for storing
     * customer order information in the ecommerce system.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();                                    // Primary key
            $table->string('customer_name');                 // Customer's full name
            $table->string('customer_phone');                // Customer's phone number
            $table->text('customer_address');                // Customer's delivery address
            $table->decimal('total_price', 10, 2);           // Total order amount
            $table->timestamps();                            // Created/updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the orders table if it exists. This is used when
     * rolling back the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

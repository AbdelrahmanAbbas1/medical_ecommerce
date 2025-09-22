<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Order Items Table
 * 
 * This migration creates the order_items table for the medical ecommerce system.
 * The order_items table acts as a pivot table between orders and products,
 * storing the quantity and price at the time of purchase for each item in an order.
 * 
 * Table Structure:
 * - id: Primary key (auto-increment)
 * - order_id: Foreign key to orders table (cascade delete)
 * - product_id: Foreign key to products table (restrict delete)
 * - quantity: Number of units ordered (required)
 * - price: Price per unit at time of purchase (required)
 * - created_at: Record creation timestamp
 * - updated_at: Record last update timestamp
 * 
 * Features:
 * - Foreign key constraints for data integrity
 * - Cascade delete for orders (if order deleted, items deleted)
 * - Restrict delete for products (prevents deletion of products with orders)
 * - Historical pricing preservation
 * - Decimal precision for accurate pricing
 * 
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the order_items table with foreign key relationships
     * to orders and products tables for storing individual order items.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();                                    // Primary key
            $table->foreignId('order_id')->constrained()->onDelete('cascade');    // Order reference (cascade delete)
            $table->foreignId('product_id')->constrained()->onDelete('restrict'); // Product reference (restrict delete)
            $table->integer('quantity');                     // Number of units ordered
            $table->decimal('price', 10, 2);                 // Price per unit at time of purchase
            $table->timestamps();                            // Created/updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the order_items table if it exists. This is used when
     * rolling back the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

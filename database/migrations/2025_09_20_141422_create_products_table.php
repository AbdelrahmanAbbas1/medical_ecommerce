<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Products Table
 * 
 * This migration creates the products table for the medical ecommerce system.
 * The products table stores all medical products available for purchase,
 * including their details, pricing, inventory, and categorization.
 * 
 * Table Structure:
 * - id: Primary key (auto-increment)
 * - name: Product name (required)
 * - description: Detailed product description (optional)
 * - price: Product price with 2 decimal places (required)
 * - stock: Available inventory quantity (required)
 * - category: Product category for organization (optional)
 * - image: Product image path (optional)
 * - deleted_at: Soft delete timestamp (nullable)
 * - created_at: Record creation timestamp
 * - updated_at: Record last update timestamp
 * 
 * Features:
 * - Soft deletes for data retention
 * - Decimal precision for accurate pricing
 * - Flexible category system
 * - Image storage support
 * 
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the products table with all necessary columns for storing
     * medical product information in the ecommerce system.
     */
    public function up(): void
    {
        Schema::create('products', function(Blueprint $table) {
            $table->id();                                    // Primary key
            $table->string('name');                          // Product name (required)
            $table->text('description')->nullable();         // Product description (optional)
            $table->decimal('price', 10, 2);                 // Price with 2 decimal places
            $table->integer('stock');                        // Available inventory quantity
            $table->string('category')->nullable();          // Product category (optional)
            $table->string('image')->nullable();             // Product image path (optional)
            $table->softDeletes();                           // Soft delete support
            $table->timestamps();                            // Created/updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the products table if it exists. This is used when
     * rolling back the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

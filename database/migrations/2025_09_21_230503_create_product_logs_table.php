<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Product Logs Table
 * 
 * This migration creates the product_logs table for the medical ecommerce system.
 * The product_logs table provides a comprehensive audit trail for all changes
 * made to products, including who made the changes and what specific changes
 * were made.
 * 
 * Table Structure:
 * - id: Primary key (auto-increment)
 * - product_id: Foreign key to products table (cascade delete)
 * - action: Type of action performed (enum: created, updated, deleted, force_deleted)
 * - changed_by: Foreign key to users table (cascade delete)
 * - changes: JSON field storing detailed change information (nullable)
 * - created_at: Log entry creation timestamp
 * - updated_at: Log entry last update timestamp
 * 
 * Features:
 * - Comprehensive audit trail for product changes
 * - User attribution for all changes
 * - JSON storage for flexible change data
 * - Enum constraint for action types
 * - Indexed for performance optimization
 * - Cascade delete relationships
 * 
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the product_logs table with foreign key relationships
     * and indexes for efficient audit trail storage and retrieval.
     */
    public function up(): void
    {
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();                                    // Primary key
            $table->foreignId('product_id')->constrained()->onDelete('cascade');    // Product reference
            $table->enum('action', ['created', 'updated', 'deleted', 'force_deleted']); // Action type
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade'); // User who made change
            $table->json('changes')->nullable();             // Detailed change information
            $table->timestamps();                            // Created/updated timestamps
            
            // Performance indexes
            $table->index(['product_id', 'action']);         // Composite index for product action queries
            $table->index('changed_by');                     // Index for user-based queries
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the product_logs table if it exists. This is used when
     * rolling back the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Users Table
 * 
 * This migration creates the users table for the medical ecommerce system.
 * The users table stores both regular customers and admin users with
 * authentication capabilities and role-based access control.
 * 
 * Table Structure:
 * - id: Primary key (auto-increment)
 * - name: User's full name (required)
 * - email: User's email address, unique (required)
 * - password: Hashed password (required)
 * - remember_token: Token for "remember me" functionality (nullable)
 * - is_admin: Boolean flag for admin privileges (default: false)
 * - created_at: Account creation timestamp
 * - updated_at: Account last update timestamp
 * 
 * Features:
 * - Unique email constraint for authentication
 * - Password hashing support
 * - Remember me functionality
 * - Admin role management
 * - Laravel authentication integration
 * 
 * @package Database\Migrations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the users table with all necessary columns for user
     * authentication and role management in the ecommerce system.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                                    // Primary key
            $table->string('name');                          // User's full name
            $table->string('email')->unique();               // Unique email address
            $table->string('password');                      // Hashed password
            $table->rememberToken();                         // Remember me token
            $table->boolean('is_admin')->default(false);     // Admin privileges flag
            $table->timestamps();                            // Created/updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the users table if it exists. This is used when
     * rolling back the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

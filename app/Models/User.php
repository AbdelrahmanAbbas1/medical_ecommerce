<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User Model
 * 
 * Represents users in the medical ecommerce system. This model handles
 * both regular customers and admin users. It extends Laravel's Authenticatable
 * class to provide authentication functionality.
 * 
 * Features:
 * - Soft deletes for data retention
 * - Admin role management
 * - Email verification support
 * - Password hashing
 * 
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating a user.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',        // User's full name
        'email',       // User's email address (used for login)
        'password',    // User's hashed password
        'is_admin',    // Boolean flag indicating admin privileges
    ];

    /**
     * The attributes that should be hidden for serialization.
     * These fields will not be included when the model is converted to JSON.
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',        // Never expose password in API responses
        'remember_token',  // Keep remember token secure
    ];

    /**
     * The attributes that should be cast to native types.
     * This ensures proper data type handling for specific fields.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',  // Cast to Carbon datetime instance
        'password' => 'hashed',             // Automatically hash password on save
    ];

    /**
     * Check if the user has admin privileges.
     * 
     * @return bool True if user is admin, false otherwise
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get the user's display name.
     * Falls back to email if name is not set.
     * 
     * @return string User's display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->email;
    }
}

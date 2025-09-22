<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductLog Model
 * 
 * Represents audit logs for product changes in the medical ecommerce system.
 * This model tracks all modifications made to products, including who made
 * the changes and what specific changes were made.
 * 
 * Features:
 * - Audit trail for product modifications
 * - Tracks user who made changes
 * - Stores detailed change information
 * - Supports different action types (created, updated, deleted)
 * 
 * @package App\Models
 */
class ProductLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating a product log entry.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',   // ID of the product that was changed
        'action',       // Type of action performed (created, updated, deleted, etc.)
        'changed_by',   // ID of the user who made the change
        'changes',      // JSON array containing the actual changes made
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => 'array',  // Cast changes to array for easy manipulation
    ];

    /**
     * Get the product that this log entry belongs to.
     * 
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who made the changes.
     * 
     * @return BelongsTo
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get a human-readable description of the changes.
     * 
     * @return string Description of what was changed
     */
    public function getChangeDescriptionAttribute(): string
    {
        switch ($this->action) {
            case 'created':
                return 'Product was created';
            case 'updated':
                $changedFields = array_keys($this->changes);
                return 'Updated: ' . implode(', ', $changedFields);
            case 'deleted':
                return 'Product was deleted';
            case 'force_deleted':
                return 'Product was permanently deleted';
            default:
                return 'Unknown action: ' . $this->action;
        }
    }

    /**
     * Get the formatted timestamp for when the change occurred.
     * 
     * @return string Formatted timestamp
     */
    public function getFormattedTimestampAttribute(): string
    {
        return $this->created_at->format('M j, Y g:i A');
    }
}

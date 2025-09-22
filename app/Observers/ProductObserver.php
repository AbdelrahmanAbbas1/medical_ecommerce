<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Support\Facades\Auth;

/**
 * ProductObserver
 * 
 * Observer class that automatically logs all changes made to Product models
 * in the medical ecommerce system. This provides a comprehensive audit trail
 * for product modifications, including who made the changes and what was changed.
 * 
 * Features:
 * - Automatic logging of product creation
 * - Detailed change tracking for updates
 * - Logging of soft deletes and force deletes
 * - User attribution for all changes
 * - JSON storage of change details
 * 
 * @package App\Observers
 */
class ProductObserver
{
    /**
     * Handle the Product "created" event.
     * 
     * This method is automatically called when a new product is created.
     * It logs the creation event with the complete product data and
     * the user who created it (if authenticated).
     *
     * @param Product $product The product that was created
     * @return void
     */
    public function created(Product $product): void
    {
        // Only log if a user is authenticated (not for system operations)
        if (Auth::check()) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'created',
                'changed_by' => Auth::id(),
                'changes' => [
                    'new_values' => $product->toArray() // Store complete product data
                ]
            ]);
        }
    }

    /**
     * Handle the Product "updated" event.
     * 
     * This method is automatically called when a product is updated.
     * It compares the original values with the new values and logs
     * only the fields that actually changed, providing a detailed
     * audit trail of what was modified.
     *
     * @param Product $product The product that was updated
     * @return void
     */
    public function updated(Product $product): void
    {
        // Only log if a user is authenticated (not for system operations)
        if (Auth::check()) {
            $changes = [];
            $original = $product->getOriginal(); // Get original values before update
            
            // Compare original vs current values to identify what changed
            foreach ($product->getDirty() as $key => $value) {
                // Skip timestamp fields as they're automatically updated
                if ($key !== 'updated_at') {
                    $changes[$key] = [
                        'old' => $original[$key] ?? null, // Original value
                        'new' => $value                    // New value
                    ];
                }
            }
            
            // Only create log entry if there were actual changes
            if (!empty($changes)) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'action' => 'updated',
                    'changed_by' => Auth::id(),
                    'changes' => $changes // Store detailed change information
                ]);
            }
        }
    }

    /**
     * Handle the Product "deleted" event (soft delete).
     * 
     * This method is automatically called when a product is soft deleted.
     * It logs the deletion event with the complete product data that was
     * deleted, allowing for potential restoration and audit purposes.
     *
     * @param Product $product The product that was soft deleted
     * @return void
     */
    public function deleted(Product $product): void
    {
        // Only log if a user is authenticated (not for system operations)
        if (Auth::check()) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'deleted',
                'changed_by' => Auth::id(),
                'changes' => [
                    'deleted_values' => $product->toArray() // Store complete product data for restoration
                ]
            ]);
        }
    }

    /**
     * Handle the Product "force deleted" event (permanent deletion).
     * 
     * This method is automatically called when a product is permanently deleted
     * from the database (bypassing soft delete). It logs the permanent deletion
     * event with the complete product data for audit purposes.
     *
     * @param Product $product The product that was permanently deleted
     * @return void
     */
    public function forceDeleted(Product $product): void
    {
        // Only log if a user is authenticated (not for system operations)
        if (Auth::check()) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'force_deleted',
                'changed_by' => Auth::id(),
                'changes' => [
                    'force_deleted_values' => $product->toArray() // Store complete product data for audit
                ]
            ]);
        }
    }
}

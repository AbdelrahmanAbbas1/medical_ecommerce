<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        if (Auth::check()) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'created',
                'changed_by' => Auth::id(),
                'changes' => [
                    'new_values' => $product->toArray()
                ]
            ]);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if (Auth::check()) {
            $changes = [];
            $original = $product->getOriginal();
            
            // Compare original vs current values
            foreach ($product->getDirty() as $key => $value) {
                if ($key !== 'updated_at') {
                    $changes[$key] = [
                        'old' => $original[$key] ?? null,
                        'new' => $value
                    ];
                }
            }
            
            if (!empty($changes)) {
                ProductLog::create([
                    'product_id' => $product->id,
                    'action' => 'updated',
                    'changed_by' => Auth::id(),
                    'changes' => $changes
                ]);
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        if (Auth::check()) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'deleted',
                'changed_by' => Auth::id(),
                'changes' => [
                    'deleted_values' => $product->toArray()
                ]
            ]);
        }
    }


    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        if (Auth::check()) {
            ProductLog::create([
                'product_id' => $product->id,
                'action' => 'force_deleted',
                'changed_by' => Auth::id(),
                'changes' => [
                    'force_deleted_values' => $product->toArray()
                ]
            ]);
        }
    }
}

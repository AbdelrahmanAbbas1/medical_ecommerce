<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

/**
 * Order Model
 * 
 * Represents customer orders in the medical ecommerce system. This model
 * handles order information, customer details, and relationships with
 * order items.
 * 
 * Features:
 * - Customer information storage
 * - Total price calculation
 * - Relationship with order items
 * - Order status tracking
 * 
 * @package App\Models
 */
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating an order.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_name',     // Customer's full name
        'customer_phone',    // Customer's phone number
        'customer_address',  // Customer's delivery address
        'total_price'        // Total order amount
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'total_price' => 'decimal:2',  // Ensure total price is stored with 2 decimal places
    ];

    /**
     * Get all items belonging to this order.
     * This relationship shows which products are included in the order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the total number of items in this order.
     * 
     * @return int Total quantity of all items
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get the formatted total price with currency symbol.
     * 
     * @return string Formatted total price string
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total_price, 2);
    }

    /**
     * Calculate and update the total price based on order items.
     * This method recalculates the total from all order items.
     * 
     * @return void
     */
    public function calculateTotal(): void
    {
        $total = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $this->update(['total_price' => $total]);
    }
}

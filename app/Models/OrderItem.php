<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * OrderItem Model
 * 
 * Represents individual items within an order. This model acts as a pivot
 * table between orders and products, storing the quantity and price at
 * the time of purchase.
 * 
 * Features:
 * - Links orders to products
 * - Stores quantity and price at time of purchase
 * - Maintains historical pricing data
 * - Calculates line totals
 * 
 * @package App\Models
 */
class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating an order item.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',     // ID of the parent order
        'product_id',   // ID of the product being ordered
        'quantity',     // Number of units ordered
        'price'         // Price per unit at time of purchase
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',    // Ensure quantity is an integer
        'price' => 'decimal:2',     // Ensure price is stored with 2 decimal places
    ];

    /**
     * Get the order that this item belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that this item represents.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price for this line item (quantity × price).
     * 
     * @return float The line total
     */
    public function getLineTotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Get the formatted line total with currency symbol.
     * 
     * @return string Formatted line total string
     */
    public function getFormattedLineTotalAttribute(): string
    {
        return '$' . number_format($this->line_total, 2);
    }

    /**
     * Get the formatted price with currency symbol.
     * 
     * @return string Formatted price string
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }
}

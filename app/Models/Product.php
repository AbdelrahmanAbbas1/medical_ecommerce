<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Product Model
 * 
 * Represents medical products in the ecommerce system. This model handles
 * product information, inventory management, and relationships with orders
 * and audit logs.
 * 
 * Features:
 * - Soft deletes for data retention
 * - Stock management
 * - Image handling
 * - Audit logging through ProductLog
 * - Relationship with order items
 * 
 * @package App\Models
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating a product.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',         // Product name
        'description',  // Detailed product description
        'price',        // Product price (decimal)
        'stock',        // Available quantity in inventory
        'category',     // Product category for organization
        'image'         // Product image path or array of paths
    ];

    /**
     * The attributes that should be cast to native types.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',  // Ensure price is stored with 2 decimal places
    ];

    /**
     * Get all order items for this product.
     * This relationship shows which orders contain this product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all audit logs for this product.
     * This relationship tracks all changes made to the product.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }

    /**
     * Get the full URL for the product's image.
     * Handles both single image strings and arrays of images.
     * Returns null if no image is set.
     * 
     * @return string|null The full URL to the product image
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Handle case where image might be stored as array or string
        $imagePath = is_array($this->image) ? $this->image[0] : $this->image;
        
        return asset('storage/' . $imagePath);
    }

    /**
     * Check if the product is in stock.
     * 
     * @return bool True if stock is greater than 0
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if the product has sufficient stock for the requested quantity.
     * 
     * @param int $quantity The quantity to check
     * @return bool True if sufficient stock is available
     */
    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
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

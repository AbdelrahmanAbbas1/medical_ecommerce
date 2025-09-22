<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }

    /**
     * Get the image URL for the product
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
}

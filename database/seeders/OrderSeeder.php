<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        if ($products->count() < 3) {
            $this->command->info('Need at least 3 products to create orders. Run ProductSeeder first.');
            return;
        }
        
        // Create 20 test orders
        for ($i = 0; $i < 20; $i++) {
            $order = Order::create([
                'customer_name' => fake()->name(),
                'customer_phone' => fake()->numerify('########'),
                'customer_address' => fake()->address(),
                'total_price' => 0, // Will be calculated
            ]);
            
            // Add 1-5 random products to each order
            $orderProducts = $products->random(rand(1, 5));
            $total = 0;
            
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $lineTotal = $product->price * $quantity;
                $total += $lineTotal;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }
            
            // Update order total
            $order->update(['total_price' => $total]);
        }
        
        $this->command->info('Created 20 test orders with order items.');
    }
}

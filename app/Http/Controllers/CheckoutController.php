<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function show() 
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty');
        }

        $items = collect($cart)->map(function($item, $productId) {
            $product = Product::find($productId);
            if (!$product) return null;
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'line_total' => $product->price * $item['quantity'],
            ];
        })->filter();

        $total = $items->sum('line_total');
        return view('checkout.show', compact('items', 'total'));
    }

    // To process the checkout
    public function process(Request $request) 
    {
    $data = $request->validate([
            'customer_name' => 'required|string|min:3|max:255',
            'customer_phone' => 'required|digits:8',
            'customer_address' => 'required|string|max:1000',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty');
        }

        // Use DB transaction and row locking to prevent overselling
        $order = null;
        DB::beginTransaction();
        try {
            $total = 0;
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'total_price' => 0, // Will be filled later
            ]);

            foreach ($cart as $productId => $item) {
                // Lock the product row
                $product = Product::lockForUpdate()->find($productId);
                if (!$product) throw ValidationException::withMessages(['cart' => 'Product not found']);
                if ($item['quantity'] > $product->stock) {
                    throw ValidationException::withMessages(['cart' => 'Product ' . $product->name . 'is out of stock or insufficient quantity']);
                }
                $line_total = $product->price * $item['quantity'];
                $total += $line_total;

                // Create Order Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                // Decrement stock
                $product->decrement('stock', $item['quantity']);
            }

            $order->update(['total_price' => $total]);

            // commit changes to the database
            DB::commit();

            // clear cart
            session()->forget('cart');

            return redirect()->route('order.confirmation', $order->id)->with('success', 'Your order was placed successfully!');

        } catch(\Exception $e) {
            DB::rollBack();

            // For development purposes
            if ($e instanceof ValidationException) {
                throw $e;
            }
            return back()->withErrors(['error' => 'An error occurred during checkout: ' . $e->getMessage()]);
        }
    }

    public function confirmation(Order $order) {
        return view('checkout.confirmation', compact('order'));
    }
}

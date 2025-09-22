<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


/**
 * CheckoutController
 * 
 * Handles the checkout process for the medical ecommerce system. This controller
 * manages the display of checkout information, order processing, and order
 * confirmation. It includes robust error handling and database transactions
 * to ensure data integrity during the checkout process.
 * 
 * Key Features:
 * - Cart validation and display
 * - Order creation with database transactions
 * - Stock management with row locking
 * - Error handling and rollback capabilities
 * - Order confirmation display
 * 
 * @package App\Http\Controllers
 */
class CheckoutController extends Controller
{
    /**
     * Display the checkout page with cart items and calculated totals.
     * 
     * This method retrieves the cart from the session, validates that it's not empty,
     * and prepares the cart items with product details and line totals for display.
     * If the cart is empty, it redirects the user back to the home page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show() 
    {
        // Retrieve cart from session - returns empty array if cart doesn't exist
        $cart = session()->get('cart', []);
        
        // Validate that cart is not empty
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty');
        }

        // Map cart items to product details and calculate line totals
        // This transforms the session cart data into a collection with full product info
        $items = collect($cart)->map(function($item, $productId) {
            $product = Product::find($productId);
            if (!$product) return null; // Skip if product no longer exists
            
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'line_total' => $product->price * $item['quantity'],
            ];
        })->filter(); // Remove null entries (deleted products)

        // Calculate total cart value by summing all line totals
        $total = $items->sum('line_total');
        
        return view('checkout.show', compact('items', 'total'));
    }

    /**
     * Process the checkout and create an order with comprehensive validation.
     * 
     * This method handles the complete order creation process including:
     * - Customer information validation
     * - Cart validation
     * - Database transaction management
     * - Stock validation with row locking to prevent overselling
     * - Order and order item creation
     * - Stock decrementation
     * - Error handling and rollback
     *
     * @param Request $request The HTTP request containing customer information
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request) 
    {
        // Validate customer information with specific rules
        $data = $request->validate([
            'customer_name' => 'required|string|min:3|max:255',    // Name must be 3-255 characters
            'customer_phone' => 'required|digits:8',               // Phone must be exactly 8 digits
            'customer_address' => 'required|string|max:1000',      // Address must be max 1000 characters
        ]);

        // Retrieve and validate cart
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty');
        }

        // Use database transaction to ensure data integrity
        // This prevents partial order creation if something goes wrong
        $order = null;
        DB::beginTransaction();
        
        try {
            $total = 0;
            
            // Create the order record first
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'total_price' => 0, // Will be calculated and updated later
            ]);

            // Process each item in the cart
            foreach ($cart as $productId => $item) {
                // Lock the product row for update to prevent race conditions
                // This ensures stock is not oversold when multiple users checkout simultaneously
                $product = Product::lockForUpdate()->find($productId);
                
                // Validate product still exists
                if (!$product) {
                    throw ValidationException::withMessages(['cart' => 'Product not found']);
                }
                
                // Validate sufficient stock is available
                if ($item['quantity'] > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Product ' . $product->name . ' is out of stock or insufficient quantity'
                    ]);
                }
                
                // Calculate line total for this item
                $line_total = $product->price * $item['quantity'];
                $total += $line_total;

                // Create order item record
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Store price at time of purchase
                ]);

                // Decrement product stock
                $product->decrement('stock', $item['quantity']);
            }

            // Update order with calculated total price
            $order->update(['total_price' => $total]);

            // Commit all changes to the database
            DB::commit();

            // Clear the cart from session since order is complete
            session()->forget('cart');

            // Redirect to confirmation page with success message
            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Your order was placed successfully!');

        } catch(\Exception $e) {
            // Rollback all database changes if any error occurs
            DB::rollBack();

            // Re-throw validation exceptions to show specific error messages
            if ($e instanceof ValidationException) {
                throw $e;
            }
            
            // Handle unexpected errors gracefully
            return back()->withErrors([
                'error' => 'An error occurred during checkout: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the order confirmation page.
     * 
     * This method shows the order confirmation page with order details
     * after a successful checkout. The order is automatically loaded
     * via route model binding.
     *
     * @param Order $order The order instance (loaded via route model binding)
     * @return \Illuminate\View\View
     */
    public function confirmation(Order $order) {
        return view('checkout.confirmation', compact('order'));
    }
}

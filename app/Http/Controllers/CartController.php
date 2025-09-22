<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


/**
 * CartController
 * 
 * Handles all shopping cart operations for the medical ecommerce system.
 * This controller manages cart display, adding products, updating quantities,
 * and removing items from the cart. All cart data is stored in the session.
 * 
 * Key Features:
 * - Cart display with product details and totals
 * - Add products to cart with stock validation
 * - Update item quantities with stock checks
 * - Remove items from cart
 * - Session-based cart storage
 * 
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
    /**
     * Display the shopping cart with all items and calculated totals.
     * 
     * This method retrieves the cart from the session, loads full product
     * details for each item, calculates line totals, and displays the
     * complete cart information to the user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve cart from session - returns empty array if cart doesn't exist
        $cart = session()->get('cart', []);
        
        // Map cart items to product details and calculate line totals
        // This transforms session cart data into a collection with full product information
        $items = collect($cart)->map(function($item, $productId) {
            $product = Product::find($productId);
            if (!$product) return null; // Skip if product no longer exists
            
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'line_total' => $product->price * $item['quantity'] // Calculate line total
            ];
        })->filter(); // Remove null entries (deleted products)

        // Calculate total cart value by summing all line totals
        $total = $items->sum('line_total');
        
        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Add a product to the shopping cart with quantity validation.
     * 
     * This method validates the requested quantity, checks stock availability,
     * and adds the product to the cart. If the product is already in the cart,
     * it increments the quantity while respecting stock limits.
     *
     * @param Request $request The HTTP request containing the quantity
     * @param Product $product The product to add (loaded via route model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, Product $product)
    {   
        // Validate the quantity input
        $data = $request->validate([
            'quantity' => 'required|integer|min:1', // Must be a positive integer
        ]);

        $qty = (int)$data['quantity'];
        
        // Check if sufficient stock is available
        if ($qty > $product->stock) {
            return back()->withErrors(['quantity' => 'Not enough stock available']);
        }

        // Retrieve current cart from session
        $cart = session()->get('cart', []);
        $id = $product->id;

        // Handle adding to cart logic
        if (isset($cart[$id])) {
            // Product already in cart - increment quantity
            $cart[$id]['quantity'] += $qty;
            
            // Ensure we don't exceed available stock
            if ($cart[$id]['quantity'] > $product->stock) {
                $cart[$id]['quantity'] = $product->stock;
            }
        } else {
            // Product not in cart - add new entry
            $cart[$id] = ['quantity' => $qty];
        }

        // Save updated cart to session
        session()->put('cart', $cart);

        return back()->with('success', 'Added to cart');
    }

    /**
     * Update the quantity of a product in the shopping cart.
     * 
     * This method validates the new quantity, checks stock availability,
     * and updates the cart with the new quantity for the specified product.
     *
     * @param Request $request The HTTP request containing the new quantity
     * @param Product $product The product to update (loaded via route model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product) 
    {
        // Validate the new quantity input
        $data = $request->validate([
            'quantity' => 'required|integer|min:1', // Must be a positive integer
        ]);

        $qty = (int)$data['quantity'];
        
        // Check if sufficient stock is available for the new quantity
        if ($qty > $product->stock) {
            return back()->withErrors(['quantity' => 'Not enough stock available.']);
        }
        
        // Retrieve current cart and update the quantity
        $cart = session()->get('cart', []);
        $cart[$product->id]['quantity'] = $qty;
        
        // Save updated cart to session
        session()->put('cart', $cart);
        
        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove a product from the shopping cart.
     * 
     * This method removes the specified product from the cart completely.
     * If the product is not in the cart, no action is taken.
     *
     * @param Request $request The HTTP request (not used but required for route)
     * @param Product $product The product to remove (loaded via route model binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request, Product $product) 
    {
        // Retrieve current cart from session
        $cart = session()->get('cart', []);
        
        // Check if product exists in cart and remove it
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Removed from cart.');
    }
}

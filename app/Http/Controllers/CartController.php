<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
 
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = collect($cart)->map(function($item, $productId) {
            $product = Product::find($productId);
            if (!$product) return null;
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'line_total' => $product->price * $item['quantity']
            ];
        })->filter();

        $total = $items->sum('line_total');
        return view('cart.index', compact('items', 'total'));
    }


    public function add(Request $request, Product $product)
     {   
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $qty = (int)$data['quantity'];
        if ($qty > $product->stock) {
            return back()->withErrors(['quantity' => 'Not enough stock available']);
        }

        $cart = session()->get('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
            if ($cart[$id]['quantity'] > $product->stock) {
                $cart[$id]['quantity'] = $product->stock;
            }
        } else {
            $cart[$id] = ['quantity' => $qty];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Added to cart');
    }


    public function update(Request $request, Product $product) 
    {
    
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $qty = (int)$data['quantity'];
        if ($qty > $product->stock) {
            return back()->withErrors(['quantity' => 'Not enough stock available.']);
        }
        $cart = session()->get('cart', []);
        $cart[$product->id]['quantity'] = $qty;
        session()->put('cart', $cart);
        return back()->with('success', 'Cart updated.');
    }


    public function remove(Request $request, Product $product) 
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Removed from cart.');
    }
}

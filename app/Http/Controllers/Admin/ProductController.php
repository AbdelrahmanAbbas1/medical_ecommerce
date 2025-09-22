<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;


/**
 * Controller for handling admin product management (CRUD, filters, uploads).
 */
class ProductController extends Controller
{
    /**
     * Display a listing of products with search and category filters.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply search filter for name, description, category
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Paginate and order by newest first, include soft-deleted
        $products = $query->withTrashed()->orderBy('created_at', 'desc')->paginate(15);
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Product::select('category')->distinct()->pluck('category');
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     * Handles image upload if provided.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display details for a specific product.
     *
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load('orderItems');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing a product.
     *
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = Product::select('category')->distinct()->pluck('category');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     * Handles image upload if provided.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Soft delete the specified product from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}

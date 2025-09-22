<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


/**
 * Controller for handling product catalog and search functionality.
 */
class ProductController extends Controller
{
    /**
     * Display the product catalog with search, filter, and sort options.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        // Get search query from request
        $q = $request->input('q');
        // Get category filter from request
        $category = $request->input('category');
        // Get sort option from request
        $sort = $request->input('sort');

        $query = Product::query();

        // Apply search filter (name or description)
        if ($q) {
            $query->where(function($sub) use ($q){
                $sub->where('name', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }

        // Apply sorting
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');            
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Fetch distinct categories for filter dropdown
        $categories = Product::select('category')->distinct()->pluck('category');

        // Paginate products and preserve query string
        $products = $query->paginate(12)->withQueryString();

        // Return view with products and categories
        return view('products.index', compact('products', 'categories'));
    }
}

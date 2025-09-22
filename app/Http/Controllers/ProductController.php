<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) {
        
        // Search filter
        $q = $request->input('q');
        // Category filter or dropdown
        $category = $request->input('category');
        // Sort filter
        $sort = $request->input('sort');

        $query = Product::query();

        if ($q) {
            $query->where(function($sub) use ($q){
                $sub->where('name', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');            
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // For category filters: fetch distinct categories (before applying filters)
        $categories = Product::select('category')->distinct()->pluck('category');

        $products = $query->paginate(12)->withQueryString();
        

        return view('products.index', compact('products', 'categories'));
    }
}

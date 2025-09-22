<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;


/**
 * Controller for handling admin order management and listing.
 */
class OrderController extends Controller
{
    /**
     * Display a listing of orders with search and date filters.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Eager load order items and products (including soft-deleted)
        $query = Order::with(['items.product' => function($query) {
            $query->withTrashed();
        }]);

        // Apply search filter for customer/order details
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_address', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by start date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        // Filter by end date
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Paginate and order by newest first
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Return view with orders
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display details for a specific order.
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Eager load items and products (including soft-deleted)
        $order->load(['items.product' => function($query) {
            $query->withTrashed();
        }]);
        return view('admin.orders.show', compact('order'));
    }
}

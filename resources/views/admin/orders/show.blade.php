@extends('layouts.app')

@section('title', 'Admin - Order Details')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0">Order Details: #{{ $order->id }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Orders
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <strong>Delivery Address:</strong>
                        <p class="mt-1">{{ $order->customer_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image_url)
                                                    <img src="{{ $item->product->image_url }}" 
                                                         alt="{{ $item->product->name ?? 'Product Image' }}" 
                                                         class="img-thumbnail me-3" style="width: 50px; height: 50px;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    @if($item->product)
                                                        <strong>{{ $item->product->name }}</strong>
                                                        @if($item->product->trashed())
                                                            <span class="badge bg-danger ms-2">Deleted</span>
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">{{ $item->product->category }}</small>
                                                    @else
                                                        <strong class="text-danger">Product Deleted</strong>
                                                        <br>
                                                        <small class="text-muted">This product is no longer available</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>KD {{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>KD {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>KD {{ number_format($order->total_price, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Status:</strong>
                        <span class="badge bg-success">Completed</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Items:</strong> {{ $order->items->count() }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Total Quantity:</strong> {{ $order->items->sum('quantity') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Order Total:</strong>
                        <h4 class="text-primary">KD {{ number_format($order->total_price, 2) }}</h4>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <small class="text-muted">
                            <strong>Ordered:</strong> {{ $order->created_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                    
                    @if($order->updated_at != $order->created_at)
                        <div class="mb-2">
                            <small class="text-muted">
                                <strong>Last Updated:</strong> {{ $order->updated_at->format('M d, Y h:i A') }}
                            </small>
                        </div>
                    @endif

                    <!-- Customer Contact Info -->
                    <hr>
                    <h6>Contact Customer</h6>
                    <div class="d-grid gap-2">
                        <a href="tel:{{ $order->customer_phone }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-telephone me-1"></i>Call {{ $order->customer_phone }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success btn-sm" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>Print Order
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-list me-1"></i>All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .btn, .card-header, .container > .d-flex:first-child {
        display: none !important;
    }
    .container {
        max-width: none !important;
    }
}
</style>
@endsection

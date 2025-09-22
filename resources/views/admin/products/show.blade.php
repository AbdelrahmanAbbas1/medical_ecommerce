@extends('layouts.app')

@section('title', 'Admin - Product Details')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0">Product Details: {{ $product->name }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Products
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Product Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-4">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-image display-4"></i>
                                        <p class="mt-2">No Image</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-8">
                            <h3>{{ $product->name }}</h3>
                            <p class="text-muted">{{ $product->category }}</p>
                            
                            @if($product->description)
                                <div class="mb-3">
                                    <h5>Description</h5>
                                    <p>{{ $product->description }}</p>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Price:</strong> KD {{ number_format($product->price, 2) }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Stock:</strong> 
                                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->stock }} units
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y') }}
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-1"></i>Edit Product
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Statistics -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Statistics</h5>
                </div>
                <div class="card-body">
                    @if($product->orderItems->count() > 0)
                        <div class="mb-3">
                            <strong>Total Orders:</strong> {{ $product->orderItems->count() }}
                        </div>
                        <div class="mb-3">
                            <strong>Total Quantity Sold:</strong> {{ $product->orderItems->sum('quantity') }}
                        </div>
                        <div class="mb-3">
                            <strong>Total Revenue:</strong> KD {{ number_format($product->orderItems->sum(function($item) { return $item->quantity * $item->price; }), 2) }}
                        </div>
                        
                        <!-- Recent Orders -->
                        <hr>
                        <h6>Recent Orders</h6>
                        <div class="list-group list-group-flush">
                            @foreach($product->orderItems->take(5) as $item)
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between">
                                        <small>Order #{{ $item->order_id }}</small>
                                        <small class="text-muted">{{ $item->quantity }}x</small>
                                    </div>
                                    <small class="text-muted">{{ $item->created_at->format('M d, Y') }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-cart-x display-6 d-block mb-2"></i>
                            <p>No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

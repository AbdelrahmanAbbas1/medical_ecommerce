@extends('layouts.app')

@section('title', 'Admin - Orders')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0">Order Management</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-box me-1"></i>Products
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search orders, customers..." class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="form-control" placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="form-control" placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $order->customer_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($order->customer_address, 30) }}</small>
                                    </div>
                                </td>
                                <td>{{ $order->customer_phone }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $order->items->count() }} items</span>
                                    <br>
                                    <small class="text-muted">{{ $order->items->sum('quantity') }} units</small>
                                </td>
                                <td>
                                    <strong>KD {{ number_format($order->total_price, 2) }}</strong>
                                </td>
                                <td>
                                    <div>
                                        {{ $order->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        No orders found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    @if($orders->count() > 0)
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $orders->total() }}</h5>
                        <p class="card-text">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">KD {{ number_format($orders->sum('total_price'), 2) }}</h5>
                        <p class="card-text">Total Revenue</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $orders->sum(function($order) { return $order->items->sum('quantity'); }) }}</h5>
                        <p class="card-text">Total Items Sold</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">KD {{ number_format($orders->avg('total_price'), 2) }}</h5>
                        <p class="card-text">Average Order Value</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

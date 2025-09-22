@extends('layouts.app')
@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Order Confirmation</h2>
    <p>Order #{{ $order->id }} placed successfully.</p>
    <p>Total: KD {{ number_format($order->total_price,2) }}</p>

    <h4>Items</h4>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->name }} x {{ $item->quantity }} — KD {{ number_format($item->price,2) }}</li>
        @endforeach
    </ul>
</div>
@endsection

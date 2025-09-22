@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Your Cart</h2>
    @if($items->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table">
            <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>
            <tbody>
            @foreach($items as $id => $item)
                <tr>
                    <td>{{ $item['product']->name }}</td>
                    <td>KD {{ number_format($item['product']->price,2) }}</td>
                    <td>
                        <form method="post" action="{{ route('cart.update', $item['product']->id) }}">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" style="width:80px">
                            <button class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                    <td>KD {{ number_format($item['line_total'],2) }}</td>
                    <td>
                        <form method="post" action="{{ route('cart.remove', $item['product']->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <h4>Total: KD {{ number_format($total,2) }}</h4>
        <a href="{{ route('checkout.show') }}" class="btn btn-success">Proceed to Checkout</a>
    @endif
</div>
@endsection

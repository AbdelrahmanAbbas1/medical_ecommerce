@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2>Checkout</h2>
    @if($items->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <div class="mb-3">
            <h4>Order Summary</h4>
            <ul>
                @foreach($items as $item)
                    <li>{{ $item['product']->name }} x {{ $item['quantity'] }} — KD {{ number_format($item['line_total'],2) }}</li>
                @endforeach
            </ul>
            <h5>Total: KD {{ number_format($total, 2) }}</h5>
        </div>

        <form method="post" action="{{ route('checkout.process') }}">
            @csrf
            <div class="mb-3">
                <label>Full name</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control" required>
                @error('customer_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="form-control" required>
                @error('customer_phone') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label>Delivery address</label>
                <textarea name="customer_address" class="form-control" required>{{ old('customer_address') }}</textarea>
                @error('customer_address') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Submit Order</button>
        </form>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
</div>
@endsection

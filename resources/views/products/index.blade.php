@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="get" class="mb-3">
        <div class="d-flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="form-control">
            <select name="category" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category')==$cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="sort" class="form-select">
                <option value="">Newest</option>
                <option value="price_asc" {{ request('sort')=='price_asc' ? 'selected':'' }}>Price ↑</option>
                <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected':'' }}>Price ↓</option>
            </select>
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://place-hold.it/300x200?text=no+image" class="card-img-top" alt="{{ $product->name }}">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="mb-1">KD {{ number_format($product->price,2) }}</p>
                    <p class="text-muted small mb-2">Stock: {{ $product->stock }}</p>

                    <form method="post" action="{{ route('cart.add', $product->id) }}" class="mt-auto">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width:80px">
                            <button class="btn btn-success" type="submit" {{ $product->stock <=0 ? 'disabled' : '' }}>Add to Cart</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $products->links() }}
</div>

@endsection
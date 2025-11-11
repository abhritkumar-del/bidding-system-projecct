@extends('layouts.app')

@section('title', 'Browse Products - Deshi Bid')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-box"></i> Browse Products
        </h2>
        <p class="text-muted">Discover amazing products</p>
    </div>
</div>

<div class="row">
    <!-- Filters -->
    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-filter"></i> Filters
                </h5>

                <form action="{{ route('products.index') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search products..." value="{{ request('search') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Apply
                    </button>

                    @if(request()->hasAny(['search', 'category']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="col-lg-9">
        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div style="overflow: hidden; height: 200px;">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                         style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                    <div style="height: 200px; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                                <h5 class="fw-bold">{{ Str::limit($product->name, 35) }}</h5>
                                <p class="text-muted small">{{ Str::limit($product->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success" style="font-size: 1.3rem;">
                                        ৳{{ number_format($product->starting_price, 2) }}
                                    </span>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box fa-4x text-muted mb-3"></i>
                    <h4>No products found</h4>
                    <p class="text-muted">Check back soon for new products!</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
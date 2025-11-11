@extends('layouts.app')

@section('title', 'Browse Auctions - Deshi Bid')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">
            <i class="fas fa-gavel"></i> Browse Auctions
        </h2>
        <p class="text-muted">Discover amazing deals through live bidding</p>
    </div>
</div>

<!-- Search & Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('auctions.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search auctions..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Auctions Grid -->
<div class="row">
    @forelse($auctions as $auction)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($auction->product->images && count($auction->product->images) > 0)
                    <img src="{{ asset('storage/' . $auction->product->images[0]) }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;"
                         alt="{{ $auction->product->name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <span class="badge bg-primary mb-2">{{ $auction->product->category->name }}</span>
                    
                    <h5 class="card-title">{{ Str::limit($auction->product->name, 40) }}</h5>
                    
                    <div class="mb-3">
                        <h3 class="text-primary mb-0">
                            ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                        </h3>
                        <small class="text-muted">
                            <i class="fas fa-gavel"></i> {{ $auction->total_bids }} bids
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-danger">
                            <i class="fas fa-clock"></i> {{ $auction->getTimeRemaining() }}
                        </span>
                    </div>
                    
                    <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary w-100">
                        <i class="fas fa-eye"></i> View Auction
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No auctions found.
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="row">
    <div class="col-12">
        {{ $auctions->links() }}
    </div>
</div>
@endsection
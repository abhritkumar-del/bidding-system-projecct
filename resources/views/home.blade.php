@extends('layouts.app')

@section('title', 'Deshi Bid - Online Bidding Platform')

@section('content')
<style>
    .hero-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 4rem 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        animation: scaleIn 0.6s ease;
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-10px) scale(1.05);
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }

    .auction-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
    }

    .auction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .product-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .auction-card:hover .product-img {
        transform: scale(1.1);
    }

    .category-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .price-tag {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2563eb;
    }

    .bid-count {
        background: #10b981;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: slideInLeft 0.8s ease;
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-3" style="color: #1f2937;">
                Welcome to <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Deshi Bid</span>
            </h1>
            <p class="lead mb-4" style="color: #6b7280;">
                Bangladesh's Most Trusted Online Bidding Platform
            </p>
            <p class="mb-4">
                Discover amazing deals through live auctions. Buy and sell unique items with confidence.
            </p>
            <div class="d-flex gap-3">
                <a href="{{ route('auctions.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-gavel"></i> Browse Auctions
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-user-plus"></i> Join Now
                    </a>
                @endguest
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <i class="fas fa-gavel" style="font-size: 15rem; color: rgba(102, 126, 234, 0.2);"></i>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="row mb-5">
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <i class="fas fa-gavel fa-3x mb-3"></i>
            <h3 class="fw-bold">{{ $stats['total_auctions'] }}</h3>
            <p class="mb-0">Active Auctions</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-box fa-3x mb-3"></i>
            <h3 class="fw-bold">{{ $stats['total_products'] }}</h3>
            <p class="mb-0">Products Listed</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="fas fa-clock fa-3x mb-3"></i>
            <h3 class="fw-bold">{{ $stats['ending_soon'] }}</h3>
            <p class="mb-0">Ending Soon</p>
        </div>
    </div>
</div>

<!-- Live Auctions Section -->
<div class="mb-5">
    <h2 class="section-title">
        <i class="fas fa-fire text-danger"></i> Live Auctions
    </h2>

    @if($liveAuctions->count() > 0)
        <div class="row">
            @foreach($liveAuctions as $auction)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="auction-card">
                        <div style="overflow: hidden;">
                            @if($auction->product->images && count($auction->product->images) > 0)
                                <img src="{{ asset('storage/' . $auction->product->images[0]) }}" 
                                     class="product-img" 
                                     alt="{{ $auction->product->name }}">
                            @else
                                <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-3">
                            <span class="category-badge mb-2">
                                {{ $auction->product->category->name }}
                            </span>
                            
                            <h5 class="mt-2 mb-2 fw-bold">
                                {{ Str::limit($auction->product->name, 30) }}
                            </h5>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="price-tag">
                                    ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                                </span>
                                <span class="bid-count">
                                    <i class="fas fa-gavel"></i> {{ $auction->total_bids }}
                                </span>
                            </div>
                            
                            <div class="auction-timer mb-3">
                                <i class="fas fa-clock"></i> {{ $auction->getTimeRemaining() }}
                            </div>
                            
                            <a href="{{ route('auctions.show', $auction) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye"></i> View Auction
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('auctions.index') }}" class="btn btn-outline-primary btn-lg">
                View All Auctions <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No active auctions at the moment. Check back soon!
        </div>
    @endif
</div>

<!-- Featured Products Section -->
<div class="mb-5">
    <h2 class="section-title">
        <i class="fas fa-star text-warning"></i> Featured Products
    </h2>

    @if($featuredProducts->count() > 0)
        <div class="row">
            @foreach($featuredProducts as $product)
                <div class="col-md-4 mb-4">
                    <div class="auction-card">
                        <div style="overflow: hidden;">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                     class="product-img" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-3">
                            <span class="category-badge mb-2">
                                {{ $product->category->name }}
                            </span>
                            
                            <h5 class="mt-2 mb-2 fw-bold">
                                {{ Str::limit($product->name, 40) }}
                            </h5>
                            
                            <p class="text-muted small mb-3">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">
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
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No featured products available.
        </div>
    @endif
</div>

<!-- Categories Section -->
<div class="mb-5">
    <h2 class="section-title">
        <i class="fas fa-th"></i> Browse by Category
    </h2>

    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('auctions.index', ['category' => $category->id]) }}" 
                   class="text-decoration-none">
                    <div class="card text-center" style="background: white; border: 2px solid #e5e7eb;">
                        <div class="card-body">
                            <i class="fas {{ $category->icon }} fa-3x mb-3" 
                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            <h6 class="fw-bold mb-2">{{ $category->name }}</h6>
                            <span class="badge bg-primary">{{ $category->active_products_count }} Products</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh page every 30 seconds for live auction updates
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection
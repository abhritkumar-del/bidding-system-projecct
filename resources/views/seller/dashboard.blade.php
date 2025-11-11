@extends('layouts.app')

@section('title', 'Seller Dashboard - Deshi Bid')

@section('content')
<style>
    .seller-stat-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    .seller-stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.6s;
    }

    .seller-stat-card:hover::before {
        animation: shine 1.5s infinite;
    }

    @keyframes shine {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }

    .product-mini-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }

    .product-mini-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-store"></i> Seller Dashboard
        </h2>
        <p class="text-muted">Manage your products and auctions</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="seller-stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <i class="fas fa-box fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['total_products'] }}</h3>
            <small>Total Products</small>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="seller-stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <i class="fas fa-clock fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['pending_products'] }}</h3>
            <small>Pending Approval</small>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="seller-stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <i class="fas fa-gavel fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['active_auctions'] }}</h3>
            <small>Active Auctions</small>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="seller-stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <i class="fas fa-check-circle fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['sold_products'] }}</h3>
            <small>Sold Products</small>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-info">
                        <i class="fas fa-box"></i> My Products
                    </a>
                    <a href="{{ route('seller.auctions.create') }}" class="btn btn-success">
                        <i class="fas fa-gavel"></i> Create Auction
                    </a>
                    <a href="{{ route('seller.auctions.index') }}" class="btn btn-warning">
                        <i class="fas fa-list"></i> My Auctions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Products -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-box"></i> Recent Products
                </h5>

                @forelse($recentProducts as $product)
                    <div class="product-mini-card">
                        <div class="me-3">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;" 
                                     alt="{{ $product->name }}">
                            @else
                                <div style="width: 60px; height: 60px; background: #e5e7eb; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ Str::limit($product->name, 30) }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-tag"></i> {{ $product->category->name }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if($product->status == 'pending')
                                <span class="badge bg-warning mb-2">Pending</span>
                            @elseif($product->status == 'approved')
                                <span class="badge bg-success mb-2">Approved</span>
                            @elseif($product->status == 'active')
                                <span class="badge bg-primary mb-2">Active</span>
                            @elseif($product->status == 'sold')
                                <span class="badge bg-secondary mb-2">Sold</span>
                            @else
                                <span class="badge bg-danger mb-2">Rejected</span>
                            @endif
                            <br>
                            <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No products yet. 
                        <a href="{{ route('seller.products.create') }}">Add your first product!</a>
                    </div>
                @endforelse

                @if($recentProducts->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-primary">
                            View All Products <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Auctions -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-gavel"></i> Recent Auctions
                </h5>

                @forelse($recentAuctions as $auction)
                    <div class="product-mini-card">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ Str::limit($auction->product->name, 35) }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $auction->start_time->format('d M, Y') }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if($auction->status == 'scheduled')
                                <span class="badge bg-info mb-2">Scheduled</span>
                            @elseif($auction->status == 'active')
                                <span class="badge bg-success mb-2">Active</span>
                            @elseif($auction->status == 'ended')
                                <span class="badge bg-secondary mb-2">Ended</span>
                            @else
                                <span class="badge bg-danger mb-2">Cancelled</span>
                            @endif
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-gavel"></i> {{ $auction->total_bids }} bids
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No auctions yet. 
                        <a href="{{ route('seller.auctions.create') }}">Create your first auction!</a>
                    </div>
                @endforelse

                @if($recentAuctions->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('seller.auctions.index') }}" class="btn btn-outline-primary">
                            View All Auctions <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tips Section -->
<div class="row">
    <div class="col-md-12">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-lightbulb"></i> Seller Tips
                </h5>
                <ul class="mb-0">
                    <li>Add clear, high-quality images of your products</li>
                    <li>Write detailed and accurate product descriptions</li>
                    <li>Set competitive starting prices to attract bidders</li>
                    <li>Respond to buyer inquiries promptly</li>
                    <li>Keep your products well-categorized for better visibility</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
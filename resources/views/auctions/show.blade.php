@extends('layouts.app')

@section('title', $auction->product->name . ' - Auction Details')

@section('content')
<style>
    .product-gallery img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .bid-history-item {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        border-left: 4px solid #10b981;
        transition: all 0.3s;
    }

    .bid-history-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .timer-box {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
</style>

<div class="row mb-4">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('auctions.index') }}">Auctions</a></li>
                <li class="breadcrumb-item active">{{ $auction->product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Product Images -->
    <div class="col-lg-6 mb-4">
        <div class="product-gallery">
            @if($auction->product->images && count($auction->product->images) > 0)
                <img src="{{ asset('storage/' . $auction->product->images[0]) }}" 
                     alt="{{ $auction->product->name }}"
                     id="mainImage">
            @else
                <div style="height: 400px; background: #f3f4f6; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @endif
        </div>

        <!-- Thumbnail Gallery -->
        @if($auction->product->images && count($auction->product->images) > 1)
            <div class="d-flex gap-2 mt-3">
                @foreach($auction->product->images as $image)
                    <img src="{{ asset('storage/' . $image) }}" 
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; cursor: pointer;"
                         onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image) }}'">
                @endforeach
            </div>
        @endif
    </div>

    <!-- Auction Details -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-body">
                <!-- Category -->
                <span class="badge mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-tag"></i> {{ $auction->product->category->name }}
                </span>

                <!-- Product Name -->
                <h2 class="fw-bold mb-3">{{ $auction->product->name }}</h2>

                <!-- Seller Info -->
                <div class="mb-3 p-3" style="background: #f9fafb; border-radius: 10px;">
                    <small class="text-muted">Seller</small>
                    <h6 class="mb-0">
                        <i class="fas fa-user"></i> {{ $auction->product->user->name }}
                    </h6>
                </div>

                <!-- Time Remaining -->
                <div class="timer-box mb-4">
                    <h5 class="mb-2">
                        <i class="fas fa-clock"></i> Time Remaining
                    </h5>
                    <h3 class="mb-0 fw-bold">{{ $auction->getTimeRemaining() }}</h3>
                </div>

                <!-- Current Bid -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="p-3" style="background: #f0fdf4; border-radius: 10px;">
                            <small class="text-muted d-block">Current Bid</small>
                            <h3 class="mb-0 fw-bold text-success">
                                ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3" style="background: #eff6ff; border-radius: 10px;">
                            <small class="text-muted d-block">Total Bids</small>
                            <h3 class="mb-0 fw-bold text-primary">
                                {{ $auction->total_bids }}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Bid Button -->
                @auth
                    @if(auth()->user()->isBidder())
                        @if($auction->isActive())
                            @if($auction->product->user_id !== auth()->id())
                                <a href="{{ route('bidder.bids.create', $auction) }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                    <i class="fas fa-gavel"></i> Place Bid
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> You cannot bid on your own product
                                </div>
                            @endif
                        @else
                            <button class="btn btn-secondary btn-lg w-100" disabled>
                                Auction {{ $auction->status }}
                            </button>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="fas fa-user-plus"></i> Register to Bid
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-sign-in-alt"></i> Login to Bid
                    </a>
                @endauth

                <!-- Auction Info -->
                <div class="mt-4 p-3" style="background: #fef3c7; border-radius: 10px;">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-info-circle"></i> Auction Information
                    </h6>
                    <ul class="mb-0" style="font-size: 0.9rem;">
                        <li>Starting Price: ৳{{ number_format($auction->product->starting_price, 2) }}</li>
                        <li>Bid Increment: ৳{{ number_format($auction->bid_increment, 2) }}</li>
                        <li>Started: {{ $auction->start_time->format('d M, Y h:i A') }}</li>
                        <li>Ends: {{ $auction->end_time->format('d M, Y h:i A') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Description & Bid History -->
<div class="row">
    <!-- Description -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-align-left"></i> Product Description
                </h5>
                <p style="white-space: pre-line;">{{ $auction->product->description }}</p>

                <hr>

                <h6 class="fw-bold mb-2">Product Details</h6>
                <ul>
                    <li><strong>Condition:</strong> {{ ucfirst($auction->product->condition) }}</li>
                    <li><strong>Category:</strong> {{ $auction->product->category->name }}</li>
                    <li><strong>Views:</strong> {{ $auction->product->views }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bid History -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-history"></i> Bid History
                </h5>

                @if($auction->bids->count() > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($auction->bids->sortByDesc('created_at')->take(10) as $bid)
                            <div class="bid-history-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $bid->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $bid->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <strong class="text-success">
                                            ৳{{ number_format($bid->amount, 2) }}
                                        </strong>
                                        @if($loop->first)
                                            <br>
                                            <span class="badge bg-success">Winning</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No bids yet. Be the first to bid!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh every 10 seconds for live updates
    setTimeout(function() {
        location.reload();
    }, 10000);
</script>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'Bidder Dashboard - Deshi Bid')

@section('content')
<style>
    .bidder-stat-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s;
        animation: bounceIn 0.8s ease;
    }

    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.5); }
        50% { opacity: 1; transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .bid-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
        border-left: 4px solid #667eea;
    }

    .bid-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .winning-badge {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
</style>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-gavel"></i> Bidder Dashboard
        </h2>
        <p class="text-muted">Track your bids and winning auctions</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('auctions.index') }}" class="btn btn-primary">
            <i class="fas fa-search"></i> Browse Auctions
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="bidder-stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <i class="fas fa-gavel fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['total_bids'] }}</h3>
            <small>Total Bids Placed</small>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="bidder-stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <i class="fas fa-fire fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['active_bids'] }}</h3>
            <small>Active Bids</small>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="bidder-stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <i class="fas fa-trophy fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['won_auctions'] }}</h3>
            <small>Won Auctions</small>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="bidder-stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
            <i class="fas fa-clock fa-3x mb-3"></i>
            <h3 class="fw-bold mb-0">{{ $stats['pending_payments'] }}</h3>
            <small>Pending Payments</small>
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
                    <a href="{{ route('auctions.index') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Browse Auctions
                    </a>
                    <a href="{{ route('bidder.bids.index') }}" class="btn btn-info">
                        <i class="fas fa-list"></i> My Bids
                    </a>
                    <a href="{{ route('bidder.won-auctions') }}" class="btn btn-success">
                        <i class="fas fa-trophy"></i> Won Auctions
                    </a>
                    <a href="{{ route('bidder.payments.index') }}" class="btn btn-warning">
                        <i class="fas fa-credit-card"></i> My Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Bids -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-fire"></i> Active Bids
                </h5>

                @forelse($activeBids as $bid)
                    <div class="bid-card">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ Str::limit($bid->auction->product->name, 35) }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-tag"></i> {{ $bid->auction->product->category->name }}
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="mb-2">
                                    @if($bid->isHighestBid())
                                        <span class="winning-badge">
                                            <i class="fas fa-crown"></i> Winning
                                        </span>
                                    @else
                                        <span class="badge bg-warning">Outbid</span>
                                    @endif
                                </div>
                                <strong class="text-primary">৳{{ number_format($bid->amount, 2) }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $bid->auction->getTimeRemaining() }}
                                </small>
                                <br>
                                <a href="{{ route('auctions.show', $bid->auction) }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No active bids. 
                        <a href="{{ route('auctions.index') }}">Start bidding now!</a>
                    </div>
                @endforelse

                @if($activeBids->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('bidder.bids.index') }}" class="btn btn-outline-primary">
                            View All Bids <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-trophy"></i> Won Auctions
                </h5>

                @forelse($wonAuctions as $auction)
                    <div class="bid-card" style="border-left-color: #10b981;">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">
                                    <i class="fas fa-trophy text-warning"></i> 
                                    {{ Str::limit($auction->product->name, 30) }}
                                </h6>
                                <small class="text-muted">
                                    Won at ৳{{ number_format($auction->current_price, 2) }}
                                </small>
                            </div>
                            <div class="text-end">
                                @if($auction->payment)
                                    @if($auction->payment->status == 'completed')
                                        <span class="badge bg-success mb-2">Paid</span>
                                    @elseif($auction->payment->status == 'pending')
                                        <span class="badge bg-warning mb-2">Payment Pending</span>
                                    @else
                                        <span class="badge bg-danger mb-2">Payment Failed</span>
                                    @endif
                                @else
                                    <a href="{{ route('bidder.payments.create', $auction) }}" class="btn btn-sm btn-warning mb-2">
                                        <i class="fas fa-credit-card"></i> Pay Now
                                    </a>
                                @endif
                                <br>
                                <a href="{{ route('auctions.show', $auction) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No won auctions yet. Keep bidding!
                    </div>
                @endforelse

                @if($wonAuctions->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('bidder.won-auctions') }}" class="btn btn-outline-success">
                            View All Won Auctions <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bidding Tips -->
<div class="row">
    <div class="col-md-12">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-lightbulb"></i> Bidding Tips
                </h5>
                <ul class="mb-0">
                    <li>Research the product value before bidding</li>
                    <li>Set a maximum budget and stick to it</li>
                    <li>Bid strategically - don't always bid at the last moment</li>
                    <li>Watch multiple auctions for the same item</li>
                    <li>Check the seller's reputation and product condition</li>
                    <li>Complete payment promptly after winning to maintain good standing</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
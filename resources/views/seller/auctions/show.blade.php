@extends('layouts.app')

@section('title', 'Auction Details - Seller Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-gavel"></i> Auction Details
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.auctions.index') }}">My Auctions</a></li>
                <li class="breadcrumb-item active">{{ $auction->product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Product & Auction Info -->
    <div class="col-lg-8 mb-4">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="fw-bold mb-3">{{ $auction->product->name }}</h4>

                <!-- Status & Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="p-3" style="background: #f0fdf4; border-radius: 10px;">
                            <small class="text-muted d-block">Current Bid</small>
                            <h3 class="text-success mb-0">
                                ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3" style="background: #eff6ff; border-radius: 10px;">
                            <small class="text-muted d-block">Total Bids</small>
                            <h3 class="text-primary mb-0">{{ $auction->total_bids }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3" style="background: #fef3c7; border-radius: 10px;">
                            <small class="text-muted d-block">Status</small>
                            <h5 class="mb-0">
                                @if($auction->status == 'scheduled')
                                    <span class="badge bg-info">Scheduled</span>
                                @elseif($auction->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($auction->status == 'ended')
                                    <span class="badge bg-secondary">Ended</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3" style="background: #fce7f3; border-radius: 10px;">
                            <small class="text-muted d-block">Views</small>
                            <h3 class="text-pink mb-0">
                                <i class="fas fa-eye"></i> {{ $auction->product->views }}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                @if($auction->product->images && count($auction->product->images) > 0)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $auction->product->images[0]) }}" 
                             class="img-fluid rounded" 
                             style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                @endif

                <!-- Product Details -->
                <h6 class="fw-bold mb-2">Product Details</h6>
                <p><strong>Category:</strong> {{ $auction->product->category->name }}</p>
                <p><strong>Condition:</strong> {{ ucfirst($auction->product->condition) }}</p>
                <p><strong>Starting Price:</strong> ৳{{ number_format($auction->product->starting_price, 2) }}</p>
                @if($auction->product->reserve_price)
                    <p><strong>Reserve Price:</strong> ৳{{ number_format($auction->product->reserve_price, 2) }}</p>
                @endif

                <hr>

                <h6 class="fw-bold mb-2">Description</h6>
                <p style="white-space: pre-line;">{{ $auction->product->description }}</p>

                <hr>

                <!-- Auction Timeline -->
                <h6 class="fw-bold mb-2">Auction Timeline</h6>
                <p><strong>Start Time:</strong> {{ $auction->start_time->format('d M, Y h:i A') }}</p>
                <p><strong>End Time:</strong> {{ $auction->end_time->format('d M, Y h:i A') }}</p>
                <p><strong>Bid Increment:</strong> ৳{{ number_format($auction->bid_increment, 2) }}</p>
                
                @if($auction->isActive())
                    <div class="alert alert-danger">
                        <strong><i class="fas fa-clock"></i> Time Remaining:</strong> {{ $auction->getTimeRemaining() }}
                    </div>
                @elseif($auction->isScheduled())
                    <div class="alert alert-info">
                        <strong><i class="fas fa-clock"></i> Starts in:</strong> {{ $auction->start_time->diffForHumans() }}
                    </div>
                @endif

                <!-- Winner Info -->
                @if($auction->winner_id && $auction->winner)
                    <div class="alert alert-success mt-3">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-trophy"></i> Auction Winner
                        </h6>
                        <p class="mb-0">
                            <strong>Winner:</strong> {{ $auction->winner->name }}<br>
                            <strong>Winning Bid:</strong> ৳{{ number_format($auction->current_price, 2) }}<br>
                            <strong>Contact:</strong> {{ $auction->winner->email }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('auctions.show', $auction) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Public Page
                    </a>
                    <a href="{{ route('seller.products.edit', $auction->product) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit"></i> Edit Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Bid History -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-history"></i> Recent Bids
                </h5>

                @if($auction->bids->count() > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($auction->bids->sortByDesc('created_at')->take(20) as $bid)
                            <div class="p-2 mb-2" style="background: #f9fafb; border-radius: 8px; border-left: 3px solid #10b981;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong>{{ $bid->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $bid->created_at->format('d M, h:i A') }}
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
                        <p class="text-muted mb-0">No bids yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh every 10 seconds for live updates
    @if($auction->isActive())
        setTimeout(function() {
            location.reload();
        }, 10000);
    @endif
</script>
@endpush
@endsection
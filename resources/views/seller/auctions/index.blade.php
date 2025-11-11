@extends('layouts.app')

@section('title', 'My Auctions - Seller Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-gavel"></i> My Auctions
        </h2>
        <p class="text-muted">Manage your product auctions</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('seller.auctions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Auction
        </a>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
           href="{{ route('seller.auctions.index') }}">
            All Auctions
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'scheduled' ? 'active' : '' }}" 
           href="{{ route('seller.auctions.index', ['status' => 'scheduled']) }}">
            Scheduled
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" 
           href="{{ route('seller.auctions.index', ['status' => 'active']) }}">
            Active
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'ended' ? 'active' : '' }}" 
           href="{{ route('seller.auctions.index', ['status' => 'ended']) }}">
            Ended
        </a>
    </li>
</ul>

@if($auctions->count() > 0)
    <div class="row">
        @foreach($auctions as $auction)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <!-- Product Image -->
                            <div class="me-3">
                                @if($auction->product->images && count($auction->product->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->product->images[0]) }}" 
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; border-radius: 10px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Auction Details -->
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">
                                    {{ Str::limit($auction->product->name, 40) }}
                                </h5>

                                <!-- Status Badge -->
                                @if($auction->status == 'scheduled')
                                    <span class="badge bg-info mb-2">Scheduled</span>
                                @elseif($auction->status == 'active')
                                    <span class="badge bg-success mb-2">Active</span>
                                @elseif($auction->status == 'ended')
                                    <span class="badge bg-secondary mb-2">Ended</span>
                                @else
                                    <span class="badge bg-danger mb-2">Cancelled</span>
                                @endif

                                <!-- Price & Bids -->
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Current Price</small>
                                        <strong class="text-success">
                                            ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                                        </strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Total Bids</small>
                                        <strong class="text-primary">
                                            <i class="fas fa-gavel"></i> {{ $auction->total_bids }}
                                        </strong>
                                    </div>
                                </div>

                                <!-- Timeline -->
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> 
                                        {{ $auction->start_time->format('d M, Y') }} - {{ $auction->end_time->format('d M, Y') }}
                                    </small>
                                    <br>
                                    @if($auction->isActive())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-clock"></i> {{ $auction->getTimeRemaining() }}
                                        </span>
                                    @elseif($auction->isScheduled())
                                        <span class="badge bg-info">
                                            <i class="fas fa-clock"></i> Starts {{ $auction->start_time->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Ended {{ $auction->end_time->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Winner Info -->
                                @if($auction->winner_id && $auction->winner)
                                    <div class="alert alert-success py-2 mb-3">
                                        <small>
                                            <i class="fas fa-trophy"></i> 
                                            <strong>Winner:</strong> {{ $auction->winner->name }}
                                        </small>
                                    </div>
                                @endif

                                <!-- Action Button -->
                                <a href="{{ route('seller.auctions.show', $auction) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $auctions->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-gavel fa-4x text-muted mb-3"></i>
            <h4>No auctions yet</h4>
            <p class="text-muted mb-4">
                You haven't created any auctions yet. Start by creating your first auction!
            </p>
            <a href="{{ route('seller.auctions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Your First Auction
            </a>
        </div>
    </div>
@endif

@push('scripts')
<script>
    // Auto refresh every 30 seconds for live updates
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection
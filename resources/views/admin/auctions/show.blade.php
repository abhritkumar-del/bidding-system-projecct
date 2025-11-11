@extends('layouts.app')

@section('title', 'Auction Details - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-gavel"></i> Auction Details
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold mb-3">{{ $auction->product->name }}</h4>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="p-3" style="background: #f0fdf4; border-radius: 10px;">
                            <small class="text-muted">Current Bid</small>
                            <h3 class="text-success mb-0">৳{{ number_format($auction->current_price, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3" style="background: #eff6ff; border-radius: 10px;">
                            <small class="text-muted">Total Bids</small>
                            <h3 class="text-primary mb-0">{{ $auction->total_bids }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3" style="background: #fef3c7; border-radius: 10px;">
                            <small class="text-muted">Status</small>
                            <h5 class="mb-0">
                                @if($auction->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($auction->status) }}</span>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-2">Product Details</h6>
                <p><strong>Category:</strong> {{ $auction->product->category->name }}</p>
                <p><strong>Condition:</strong> {{ ucfirst($auction->product->condition) }}</p>
                <p><strong>Seller:</strong> {{ $auction->product->user->name }}</p>
                
                <h6 class="fw-bold mt-4 mb-2">Auction Timeline</h6>
                <p><strong>Start:</strong> {{ $auction->start_time->format('d M, Y h:i A') }}</p>
                <p><strong>End:</strong> {{ $auction->end_time->format('d M, Y h:i A') }}</p>
                
                @if($auction->winner)
                    <div class="alert alert-success mt-4">
                        <h6 class="fw-bold"><i class="fas fa-trophy"></i> Winner</h6>
                        <p class="mb-0">{{ $auction->winner->name }} - ৳{{ number_format($auction->current_price, 2) }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="fas fa-history"></i> Bid History</h5>
                @if($auction->bids->count() > 0)
                    @foreach($auction->bids->sortByDesc('created_at')->take(10) as $bid)
                        <div class="p-2 mb-2" style="background: #f9fafb; border-radius: 8px;">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $bid->user->name }}</strong><br>
                                    <small class="text-muted">{{ $bid->created_at->diffForHumans() }}</small>
                                </div>
                                <strong class="text-success">৳{{ number_format($bid->amount, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No bids yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
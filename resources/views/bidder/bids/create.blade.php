@extends('layouts.app')

@section('title', 'Place Bid - ' . $auction->product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4 text-center">
                    <i class="fas fa-gavel"></i> Place Your Bid
                </h3>

                <div class="mb-4 p-3" style="background: #f9fafb; border-radius: 10px;">
                    <h6 class="fw-bold">{{ $auction->product->name }}</h6>
                    <small class="text-muted">{{ $auction->product->category->name }}</small>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <div class="p-3" style="background: #eff6ff; border-radius: 10px;">
                            <small class="text-muted d-block">Current Bid</small>
                            <h4 class="mb-0 fw-bold text-primary">
                                ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3" style="background: #fef3c7; border-radius: 10px;">
                            <small class="text-muted d-block">Minimum Bid</small>
                            <h4 class="mb-0 fw-bold text-warning">
                                ৳{{ number_format($minimumBid, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <form action="{{ route('bidder.bids.store', $auction) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill-wave"></i> Your Bid Amount
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">৳</span>
                            <input type="number" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   name="amount" 
                                   min="{{ $minimumBid }}"
                                   step="0.01"
                                   value="{{ old('amount', $minimumBid) }}"
                                   required
                                   autofocus>
                        </div>
                        @error('amount')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Minimum bid: ৳{{ number_format($minimumBid, 2) }}
                        </small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Note:</strong> Once you place a bid, it cannot be cancelled.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="fas fa-gavel"></i> Confirm Bid
                        </button>
                        <a href="{{ route('auctions.show', $auction) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
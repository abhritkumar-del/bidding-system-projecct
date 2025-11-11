@extends('layouts.app')

@section('title', $product->name . ' - Deshi Bid')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                         class="img-fluid rounded mb-3" 
                         alt="{{ $product->name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 400px;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                @endif
                
                <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                
                <span class="badge bg-primary mb-3">{{ $product->category->name }}</span>
                
                <div class="mb-4">
                    <h6 class="fw-bold">Description:</h6>
                    <p>{{ $product->description }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Condition:</h6>
                    <span class="badge bg-info">{{ ucfirst($product->condition) }}</span>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Seller:</h6>
                    <p>{{ $product->user->name }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-tag"></i> Pricing
                </h5>
                
                <div class="mb-3">
                    <h6>Starting Price:</h6>
                    <h3 class="text-primary">৳{{ number_format($product->starting_price, 2) }}</h3>
                </div>
                
                @if($product->reserve_price)
                    <div class="mb-3">
                        <h6>Reserve Price:</h6>
                        <h4 class="text-warning">৳{{ number_format($product->reserve_price, 2) }}</h4>
                    </div>
                @endif
                
                @if($product->buy_now_price)
                    <div class="mb-3">
                        <h6>Buy Now Price:</h6>
                        <h4 class="text-success">৳{{ number_format($product->buy_now_price, 2) }}</h4>
                    </div>
                @endif
                
                @if($product->auction && $product->auction->isActive())
                    <a href="{{ route('auctions.show', $product->auction) }}" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-gavel"></i> Go to Auction
                    </a>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No active auction
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
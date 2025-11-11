@extends('layouts.app')

@section('title', 'Product Details - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-box"></i> Product Details
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name ?? 'Product Details' }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Product Images & Info -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <!-- Images -->
                @if($product->images && count($product->images) > 0)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $product->images[0]) }}" 
                             class="img-fluid rounded" 
                             style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                    
                    @if(count($product->images) > 1)
                        <div class="d-flex gap-2 mb-4">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image) }}" 
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; cursor: pointer;">
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded mb-4" 
                         style="height: 400px;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                @endif

                <h3 class="fw-bold mb-3">{{ $product->name ?? 'N/A' }}</h3>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Category:</strong> 
                            <span class="badge bg-primary">{{ optional($product->category)->name ?? 'Uncategorized' }}</span>
                        </p>
                        <p><strong>Condition:</strong> 
                            <span class="badge bg-info">{{ ucfirst($product->condition) }}</span>
                        </p>
                        <p><strong>Status:</strong> 
                            @if($product->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($product->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($product->status == 'active')
                                <span class="badge bg-primary">Active</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($product->status) }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Starting Price:</strong> 
                            <span class="text-success fw-bold">৳{{ number_format($product->starting_price ?? 0, 2) }}</span>
                        </p>
                        @if($product->reserve_price)
                            <p><strong>Reserve Price:</strong> 
                                <span class="text-warning fw-bold">৳{{ number_format($product->reserve_price, 2) }}</span>
                            </p>
                        @endif
                        <p><strong>Views:</strong> {{ $product->views ?? 0 }}</p>
                    </div>
                </div>

                <hr>

                <h5 class="fw-bold mb-3">Description</h5>
                <p style="white-space: pre-line;">{{ $product->description }}</p>

                @if($product->rejection_reason)
                    <div class="alert alert-danger mt-4">
                        <strong><i class="fas fa-exclamation-triangle"></i> Rejection Reason:</strong>
                        <p class="mb-0 mt-2">{{ $product->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Seller Info & Actions -->
    <div class="col-lg-4">
        <!-- Seller Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-user"></i> Seller Information
                </h5>
                <p><strong>Name:</strong> {{ optional($product->user)->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ optional($product->user)->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ optional($product->user)->phone ?? 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    @if(optional($product->user)->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">{{ ucfirst(optional($product->user)->status ?? 'inactive') }}</span>
                    @endif
                </p>
                <p class="mb-0"><strong>Listed:</strong> {{ optional($product->created_at)->format('d M, Y h:i A') ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Actions -->
        @if($product->status == 'pending')
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-cog"></i> Actions
                    </h5>
                    
                    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Approve this product?')">
                            <i class="fas fa-check"></i> Approve Product
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger w-100" 
                            data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Reject Product
                    </button>
                </div>
            </div>
        @endif

        <!-- Auction Info -->
        @if($product->auction)
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-gavel"></i> Auction Information
                    </h5>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $product->auction->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($product->auction->status) }}
                        </span>
                    </p>
                    <p><strong>Current Price:</strong> ৳{{ number_format($product->auction->current_price, 2) }}</p>
                    <p><strong>Total Bids:</strong> {{ $product->auction->total_bids }}</p>
                    <p><strong>Starts:</strong> {{ $product->auction->start_time->format('d M, Y h:i A') }}</p>
                    <p class="mb-0"><strong>Ends:</strong> {{ $product->auction->end_time->format('d M, Y h:i A') }}</p>
                    
                    <a href="{{ route('admin.auctions.show', $product->auction) }}" class="btn btn-primary w-100 mt-3">
                        <i class="fas fa-eye"></i> View Auction
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        The seller will be notified of the rejection reason.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for rejection *</label>
                        <textarea class="form-control" name="reason" rows="4" required 
                                  placeholder="Please provide a clear reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
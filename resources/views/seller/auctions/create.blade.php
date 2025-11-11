@extends('layouts.app')

@section('title', 'Create Auction - Seller Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-plus"></i> Create New Auction
        </h2>
        <p class="text-muted">Schedule an auction for your approved product</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        @if($products->count() == 0)
            <div class="alert alert-warning">
                <h5 class="fw-bold">
                    <i class="fas fa-exclamation-triangle"></i> No Products Available
                </h5>
                <p class="mb-3">You don't have any approved products available for auction.</p>
                <p class="mb-0">
                    <strong>Please note:</strong>
                </p>
                <ul class="mb-3">
                    <li>Only approved products can be auctioned</li>
                    <li>Products with active auctions cannot be auctioned again</li>
                </ul>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>
        @else
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('seller.auctions.store') }}" method="POST">
                        @csrf

                        <!-- Select Product -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-box"></i> Select Product *
                            </label>
                            <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">Choose a product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - ৳{{ number_format($product->starting_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only approved products without active auctions are shown</small>
                        </div>

                        <!-- Start Time -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-alt"></i> Start Time *
                            </label>
                            <input type="datetime-local" 
                                   name="start_time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   value="{{ old('start_time') }}"
                                   min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Auction must start at least 1 hour from now</small>
                        </div>

                        <!-- End Time -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-check"></i> End Time *
                            </label>
                            <input type="datetime-local" 
                                   name="end_time" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   value="{{ old('end_time') }}"
                                   min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">End time must be after start time</small>
                        </div>

                        <!-- Bid Increment -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-plus-circle"></i> Bid Increment (৳) *
                            </label>
                            <input type="number" 
                                   name="bid_increment" 
                                   step="1" 
                                   min="1"
                                   class="form-control @error('bid_increment') is-invalid @enderror" 
                                   value="{{ old('bid_increment', 100) }}"
                                   required
                                   placeholder="100">
                            @error('bid_increment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum amount by which bids must increase</small>
                        </div>

                        <!-- Quick Duration Buttons -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-clock"></i> Quick Duration
                            </label>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDuration(1)">
                                    1 Day
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDuration(3)">
                                    3 Days
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDuration(7)">
                                    7 Days
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDuration(14)">
                                    14 Days
                                </button>
                            </div>
                            <small class="text-muted">Click to auto-fill start and end times</small>
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-info">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-info-circle"></i> Auction Guidelines
                            </h6>
                            <ul class="mb-0">
                                <li>Set realistic start and end times</li>
                                <li>Bid increment should be reasonable (usually 5-10% of starting price)</li>
                                <li>Once auction starts, it cannot be cancelled if bids are placed</li>
                                <li>Auction will automatically end at the specified time</li>
                            </ul>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i> Create Auction
                            </button>
                            <a href="{{ route('seller.auctions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function setDuration(days) {
        const now = new Date();
        const startTime = new Date(now.getTime() + 60 * 60 * 1000); // 1 hour from now
        const endTime = new Date(startTime.getTime() + days * 24 * 60 * 60 * 1000);
        
        // Format datetime-local input
        const formatDateTime = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        };
        
        document.querySelector('input[name="start_time"]').value = formatDateTime(startTime);
        document.querySelector('input[name="end_time"]').value = formatDateTime(endTime);
    }
</script>
@endpush
@endsection
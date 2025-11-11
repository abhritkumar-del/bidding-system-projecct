@extends('layouts.app')

@section('title', 'My Products - Seller Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-box"></i> My Products
        </h2>
        <p class="text-muted">Manage your products</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>
</div>

@if($products->count() > 0)
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div style="overflow: hidden; height: 180px;">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                 style="width: 100%; height: 180px; object-fit: cover;">
                        @else
                            <div style="height: 180px; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold">{{ Str::limit($product->name, 40) }}</h6>
                        <p class="text-muted small mb-2">{{ $product->category->name }}</p>
                        
                        @if($product->status == 'pending')
                            <span class="badge bg-warning">Pending Approval</span>
                        @elseif($product->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($product->status == 'active')
                            <span class="badge bg-primary">Active</span>
                        @elseif($product->status == 'sold')
                            <span class="badge bg-secondary">Sold</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif

                        <div class="mt-3">
                            <strong class="text-success">৳{{ number_format($product->starting_price, 2) }}</strong>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            @if($product->status != 'sold')
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" 
                                        onclick="return confirm('Delete this product?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-box fa-4x text-muted mb-3"></i>
            <h4>No products yet</h4>
            <p class="text-muted mb-4">Start by adding your first product!</p>
            <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Your First Product
            </a>
        </div>
    </div>
@endif
@endsection
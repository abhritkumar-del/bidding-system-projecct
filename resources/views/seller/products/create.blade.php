@extends('layouts.app')

@section('title', 'Add New Product - Seller Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-plus"></i> Add New Product
        </h2>
        <p class="text-muted">List your product for auction</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag"></i> Product Name *
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="e.g., iPhone 13 Pro Max">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-list"></i> Category *
                        </label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-align-left"></i> Description *
                        </label>
                        <textarea name="description" rows="5" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  required placeholder="Describe your product in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Condition -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-info-circle"></i> Condition *
                        </label>
                        <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                            <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Used</option>
                            <option value="refurbished" {{ old('condition') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                        </select>
                        @error('condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Starting Price -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill-wave"></i> Starting Price (৳) *
                        </label>
                        <input type="number" name="starting_price" step="0.01" min="1"
                               class="form-control @error('starting_price') is-invalid @enderror" 
                               value="{{ old('starting_price') }}" required placeholder="1000">
                        @error('starting_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reserve Price (Optional) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-shield-alt"></i> Reserve Price (৳) - Optional
                        </label>
                        <input type="number" name="reserve_price" step="0.01" min="1"
                               class="form-control @error('reserve_price') is-invalid @enderror" 
                               value="{{ old('reserve_price') }}" placeholder="Minimum price you'll accept">
                        <small class="text-muted">The minimum price at which the item will be sold</small>
                        @error('reserve_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Images -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-images"></i> Product Images
                        </label>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="form-control @error('images.*') is-invalid @enderror">
                        <small class="text-muted">You can upload multiple images (Max 2MB each)</small>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Your product will be reviewed by admin before being listed for auction.
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check"></i> Submit for Approval
                        </button>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
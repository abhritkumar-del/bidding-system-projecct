@extends('layouts.app')

@section('title', 'Create Category - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-plus"></i> Create New Category
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <!-- Category Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag"></i> Category Name *
                        </label>
                        <input type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="e.g., Mobile Phones">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">A unique name for the category</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea name="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Brief description of the category...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-icons"></i> Font Awesome Icon Class
                        </label>
                        <input type="text" name="icon" 
                               class="form-control @error('icon') is-invalid @enderror" 
                               value="{{ old('icon', 'fa-box') }}" 
                               placeholder="fa-box">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Find icons at <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a>
                            (e.g., fa-mobile-alt, fa-laptop, fa-tv)
                        </small>
                    </div>

                    <!-- Icon Preview -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon Preview:</label>
                        <div class="p-3" style="background: #f9fafb; border-radius: 10px;">
                            <i class="fas fa-box fa-3x" id="iconPreview" style="color: #667eea;"></i>
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sort-numeric-down"></i> Display Order
                        </label>
                        <input type="number" name="order" 
                               class="form-control @error('order') is-invalid @enderror" 
                               value="{{ old('order', 0) }}" 
                               placeholder="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" 
                                   id="is_active" checked>
                            <label class="form-check-label fw-bold" for="is_active">
                                Active Category
                            </label>
                        </div>
                        <small class="text-muted">Only active categories will be visible to users</small>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> The category slug will be automatically generated from the name.
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Create Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Icon Preview
    document.querySelector('input[name="icon"]').addEventListener('input', function(e) {
        const iconClass = e.target.value || 'fa-box';
        document.getElementById('iconPreview').className = 'fas ' + iconClass + ' fa-3x';
    });
</script>
@endpush
@endsection
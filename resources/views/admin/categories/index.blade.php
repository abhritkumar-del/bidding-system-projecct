@extends('layouts.app')

@section('title', 'Manage Categories - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-th"></i> Manage Categories
        </h2>
        <p class="text-muted">Organize products into categories</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Active Products</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                <i class="fas {{ $category->icon }} fa-2x" style="color: #667eea;"></i>
                            </td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $category->active_products_count }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $category->order }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.categories.toggle', $category) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning"
                                                title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    
                                    @if($category->products_count == 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p>No categories found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
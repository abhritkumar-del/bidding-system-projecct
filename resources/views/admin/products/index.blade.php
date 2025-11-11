@extends('layouts.app')

@section('title', 'Manage Products - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-box"></i> Manage Products
        </h2>
        <p class="text-muted">Review and manage all products</p>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
           href="{{ route('admin.products.index') }}">
            All Products
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
           href="{{ route('admin.products.index', ['status' => 'pending']) }}">
            Pending <span class="badge bg-warning">{{ \App\Models\Product::pending()->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}" 
           href="{{ route('admin.products.index', ['status' => 'approved']) }}">
            Approved
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" 
           href="{{ route('admin.products.index', ['status' => 'active']) }}">
            Active
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" 
           href="{{ route('admin.products.index', ['status' => 'rejected']) }}">
            Rejected
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Seller</th>
                        <th>Category</th>
                        <th>Starting Price</th>
                        <th>Status</th>
                        <th>Listed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div style="width: 60px; height: 60px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ Str::limit($product->name, 30) }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> {{ $product->views }} views
                                </small>
                            </td>
                            <td>{{ $product->user->name }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                            </td>
                            <td class="fw-bold text-success">
                                ৳{{ number_format($product->starting_price, 2) }}
                            </td>
                            <td>
                                @if($product->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($product->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($product->status == 'active')
                                    <span class="badge bg-primary">Active</span>
                                @elseif($product->status == 'sold')
                                    <span class="badge bg-secondary">Sold</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('d M, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($product->status == 'pending')
                                        <form action="{{ route('admin.products.approve', $product) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success"
                                                    onclick="return confirm('Approve this product?')" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $product->id }}" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Delete this product permanently?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        @if($product->status == 'pending')
                            <div class="modal fade" id="rejectModal{{ $product->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Product:</strong> {{ $product->name }}</p>
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
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <p>No products found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
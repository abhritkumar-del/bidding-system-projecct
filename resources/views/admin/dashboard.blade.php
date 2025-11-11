@extends('layouts.app')

@section('title', 'Admin Dashboard - Deshi Bid')

@section('content')
<style>
    .stat-box {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
        border-left: 5px solid;
        animation: slideInRight 0.6s ease;
    }

    .stat-box:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .data-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
    }

    .quick-action-btn {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s;
    }
</style>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-tachometer-alt"></i> Admin Dashboard
        </h2>
        <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<!-- User Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-box" style="border-color: #3b82f6;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h3>
                    <small class="text-muted">Total Users</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-box" style="border-color: #10b981;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fas fa-store"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['total_sellers'] }}</h3>
                    <small class="text-muted">Sellers</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-box" style="border-color: #8b5cf6;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['total_bidders'] }}</h3>
                    <small class="text-muted">Bidders</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-box" style="border-color: #f59e0b;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['pending_users'] }}</h3>
                    <small class="text-muted">Pending Users</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product & Auction Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-box" style="border-color: #ec4899;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['total_products'] }}</h3>
                    <small class="text-muted">Total Products</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-box" style="border-color: #f59e0b;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['pending_products'] }}</h3>
                    <small class="text-muted">Pending Approval</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-box" style="border-color: #3b82f6;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">{{ $stats['active_auctions'] }}</h3>
                    <small class="text-muted">Active Auctions</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-box" style="border-color: #10b981;">
            <div class="d-flex align-items-center">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h3 class="mb-0 fw-bold">৳{{ number_format($stats['total_revenue'], 2) }}</h3>
                    <small class="text-muted">Total Revenue</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="quick-action-btn btn btn-primary">
                        <i class="fas fa-th"></i> Manage Categories
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="quick-action-btn btn btn-info">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="quick-action-btn btn btn-warning">
                        <i class="fas fa-box"></i> Approve Products
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="quick-action-btn btn btn-success">
                        <i class="fas fa-dollar-sign"></i> Manage Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Payments -->
@if($pendingPayments->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="data-table">
            <div class="p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-exclamation-circle text-warning"></i> Pending Payments
                </h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingPayments as $payment)
                            <tr>
                                <td><code>{{ $payment->transaction_id }}</code></td>
                                <td>{{ $payment->user->name }}</td>
                                <td>{{ $payment->auction->product->name }}</td>
                                <td class="fw-bold text-success">৳{{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ strtoupper($payment->payment_method) }}</span>
                                </td>
                                <td>{{ $payment->created_at->format('d M, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this payment?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payment->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $payment->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for rejection</label>
                                                    <textarea class="form-control" name="reason" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Reject Payment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Products -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="data-table">
            <div class="p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-box"></i> Recent Products
                </h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Seller</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                            <tr>
                                <td>{{ Str::limit($product->name, 30) }}</td>
                                <td>{{ $product->user->name }}</td>
                                <td>
                                    @if($product->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($product->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($product->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="data-table">
            <div class="p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-users"></i> Recent Users
                </h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    @if($user->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($user->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Banned</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
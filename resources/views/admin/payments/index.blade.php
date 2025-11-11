@extends('layouts.app')

@section('title', 'Payment Management - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold"><i class="fas fa-dollar-sign"></i> Payment Management</h2>
        <p class="text-muted">Review and process payments</p>
    </div>
</div>

<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
           href="{{ route('admin.payments.index') }}">All Payments</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
           href="{{ route('admin.payments.index', ['status' => 'pending']) }}">
            Pending <span class="badge bg-warning">{{ \App\Models\Payment::pending()->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
           href="{{ route('admin.payments.index', ['status' => 'completed']) }}">Completed</a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td><code>{{ $payment->transaction_id }}</code></td>
                            <td>
                                <strong>{{ $payment->user->name }}</strong><br>
                                <small class="text-muted">{{ $payment->user->email }}</small>
                            </td>
                            <td>{{ Str::limit($payment->auction->product->name, 30) }}</td>
                            <td class="fw-bold text-success">৳{{ number_format($payment->amount, 2) }}</td>
                            <td><span class="badge bg-info">{{ strtoupper($payment->payment_method) }}</span></td>
                            <td>
                                @if($payment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($payment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>{{ $payment->created_at->format('d M, Y') }}</td>
                            <td>
                                @if($payment->status == 'pending')
                                    <form action="{{ route('admin.payments.approve', $payment) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Approve this payment?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $payment->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                                                <label class="form-label fw-bold">Reason *</label>
                                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection
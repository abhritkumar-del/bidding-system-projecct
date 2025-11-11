@extends('layouts.app')

@section('title', 'My Payments - Deshi Bid')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-credit-card"></i> My Payments
        </h2>
        <p class="text-muted">Track your auction payments</p>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
           href="{{ route('bidder.payments.index') }}">
            All Payments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
           href="{{ route('bidder.payments.index', ['status' => 'pending']) }}">
            Pending
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
           href="{{ route('bidder.payments.index', ['status' => 'completed']) }}">
            Completed
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'failed' ? 'active' : '' }}" 
           href="{{ route('bidder.payments.index', ['status' => 'failed']) }}">
            Failed
        </a>
    </li>
</ul>

@if($payments->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <code>{{ $payment->transaction_id }}</code>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($payment->auction->product->name, 30) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-tag"></i> {{ $payment->auction->product->category->name }}
                                    </small>
                                </td>
                                <td class="fw-bold text-success">
                                    ৳{{ number_format($payment->amount, 2) }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ strtoupper($payment->payment_method) }}
                                    </span>
                                    @if($payment->sender_number)
                                        <br>
                                        <small class="text-muted">{{ $payment->sender_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($payment->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($payment->status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->paid_at ? $payment->paid_at->format('d M, Y') : '-' }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('bidder.payments.show', $payment) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($payment->status == 'pending' || $payment->status == 'failed')
                                            <a href="{{ route('bidder.payments.create', $payment->auction) }}" 
                                               class="btn btn-outline-warning" title="Update Payment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
            <h4>No Payments Yet</h4>
            <p class="text-muted">
                You don't have any payments yet. Win an auction to make your first payment!
            </p>
            <a href="{{ route('auctions.index') }}" class="btn btn-primary">
                <i class="fas fa-gavel"></i> Browse Auctions
            </a>
        </div>
    </div>
@endif
@endsection
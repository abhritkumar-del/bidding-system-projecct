@extends('layouts.app')

@section('title', 'Payment Details - Deshi Bid')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-receipt"></i> Payment Details
        </h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('bidder.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bidder.payments.index') }}">Payments</a></li>
                <li class="breadcrumb-item active">{{ $payment->transaction_id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Payment Information -->
    <div class="col-lg-8 mb-4">
        <div class="card mb-4">
            <div class="card-body">
                <!-- Status Badge -->
                <div class="mb-4">
                    @if($payment->status == 'pending')
                        <span class="badge bg-warning" style="font-size: 1.2rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-clock"></i> Pending Approval
                        </span>
                    @elseif($payment->status == 'completed')
                        <span class="badge bg-success" style="font-size: 1.2rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-check-circle"></i> Payment Completed
                        </span>
                    @else
                        <span class="badge bg-danger" style="font-size: 1.2rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-times-circle"></i> Payment Failed
                        </span>
                    @endif
                </div>

                <h4 class="fw-bold mb-4">Payment Information</h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Transaction ID:</strong></p>
                        <code style="font-size: 1.1rem;">{{ $payment->transaction_id }}</code>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Amount:</strong></p>
                        <h3 class="text-success mb-0">৳{{ number_format($payment->amount, 2) }}</h3>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Payment Method:</strong></p>
                        <span class="badge bg-info" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            {{ strtoupper($payment->payment_method) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        @if($payment->sender_number)
                            <p class="mb-2"><strong>Sender Number:</strong></p>
                            <p class="mb-0">{{ $payment->sender_number }}</p>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Submitted At:</strong></p>
                        <p class="mb-0">{{ $payment->paid_at ? $payment->paid_at->format('d M, Y h:i A') : '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($payment->approved_at)
                            <p class="mb-2"><strong>Approved At:</strong></p>
                            <p class="mb-0">{{ $payment->approved_at->format('d M, Y h:i A') }}</p>
                        @endif
                    </div>
                </div>

                @if($payment->admin_notes)
                    <hr>
                    <div class="alert alert-{{ $payment->status == 'completed' ? 'success' : 'danger' }}">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-comment"></i> Admin Notes
                        </h6>
                        <p class="mb-0">{{ $payment->admin_notes }}</p>
                    </div>
                @endif

                @if($payment->approvedBy)
                    <hr>
                    <p class="text-muted mb-0">
                        <small>
                            Processed by: {{ $payment->approvedBy->name }}
                        </small>
                    </p>
                @endif
            </div>
        </div>

        <!-- Payment Proof -->
        @if($payment->payment_proof)
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-image"></i> Payment Screenshot
                    </h5>
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" 
                         class="img-fluid rounded" 
                         alt="Payment Proof"
                         style="max-height: 500px; width: 100%; object-fit: contain; background: #f3f4f6;">
                </div>
            </div>
        @endif
    </div>

    <!-- Auction Details Sidebar -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-trophy text-warning"></i> Auction Details
                </h5>

                @if($payment->auction->product->images && count($payment->auction->product->images) > 0)
                    <img src="{{ asset('storage/' . $payment->auction->product->images[0]) }}" 
                         class="img-fluid rounded mb-3"
                         style="width: 100%; height: 200px; object-fit: cover;">
                @endif

                <h6 class="fw-bold">{{ $payment->auction->product->name }}</h6>
                <p class="text-muted small">{{ $payment->auction->product->category->name }}</p>

                <hr>

                <p class="mb-2">
                    <strong>Seller:</strong><br>
                    {{ $payment->auction->product->user->name }}
                </p>

                <p class="mb-2">
                    <strong>Winning Bid:</strong><br>
                    <span class="text-success fw-bold">৳{{ number_format($payment->auction->current_price, 2) }}</span>
                </p>

                <p class="mb-2">
                    <strong>Auction Ended:</strong><br>
                    {{ $payment->auction->end_time->format('d M, Y') }}
                </p>

                <hr>

                <div class="d-grid gap-2">
                    <a href="{{ route('auctions.show', $payment->auction) }}" 
                       class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Auction
                    </a>

                    @if($payment->status == 'pending' || $payment->status == 'failed')
                        <a href="{{ route('bidder.payments.create', $payment->auction) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Update Payment
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
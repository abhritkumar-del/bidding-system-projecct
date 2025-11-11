@extends('layouts.app')

@section('title', 'Submit Payment - Deshi Bid')

@section('content')
<style>
    .payment-method-card {
        border: 3px solid #e5e7eb;
        border-radius: 15px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .payment-method-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .payment-method-card.active {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }

    .payment-method-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-credit-card"></i> Submit Payment
        </h2>
        <p class="text-muted">Pay for your won auction</p>
    </div>
</div>

<div class="row">
    <!-- Product Info -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-trophy text-warning"></i> Won Auction
                </h5>

                @if($auction->product->images && count($auction->product->images) > 0)
                    <img src="{{ asset('storage/' . $auction->product->images[0]) }}" 
                         class="img-fluid rounded mb-3"
                         style="width: 100%; height: 200px; object-fit: cover;">
                @endif

                <h6 class="fw-bold">{{ $auction->product->name }}</h6>
                <p class="text-muted small mb-3">{{ $auction->product->category->name }}</p>

                <div class="alert alert-success">
                    <strong>Winning Bid:</strong><br>
                    <h3 class="mb-0">৳{{ number_format($auction->current_price, 2) }}</h3>
                </div>

                <hr>

                <p class="mb-2">
                    <strong>Auction Ended:</strong><br>
                    {{ $auction->end_time->format('d M, Y h:i A') }}
                </p>
                <p class="mb-0">
                    <strong>Seller:</strong><br>
                    {{ $auction->product->user->name }}
                </p>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body p-4">
                @if($existingPayment && $existingPayment->status == 'pending')
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-clock"></i>
                        <strong>Pending Payment:</strong> Your previous payment is under review. You can update the information below if needed.
                    </div>
                @elseif($existingPayment && $existingPayment->status == 'failed')
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Payment Failed:</strong> {{ $existingPayment->admin_notes ?? 'Please resubmit your payment with correct information.' }}
                    </div>
                @endif

                <form action="{{ route('bidder.payments.store', $auction) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-wallet"></i> Select Payment Method
                    </h5>

                    <!-- Payment Method Selection -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="payment-method-card {{ old('payment_method', $existingPayment->payment_method ?? '') == 'bkash' ? 'active' : '' }}" 
                                 onclick="selectPaymentMethod('bkash')">
                                <input type="radio" name="payment_method" value="bkash" id="method-bkash" class="d-none" 
                                       {{ old('payment_method', $existingPayment->payment_method ?? '') == 'bkash' ? 'checked' : '' }}>
                                <i class="fas fa-mobile-alt" style="color: #E2136E;"></i>
                                <h6 class="fw-bold mb-0">bKash</h6>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="payment-method-card {{ old('payment_method', $existingPayment->payment_method ?? '') == 'nagad' ? 'active' : '' }}" 
                                 onclick="selectPaymentMethod('nagad')">
                                <input type="radio" name="payment_method" value="nagad" id="method-nagad" class="d-none"
                                       {{ old('payment_method', $existingPayment->payment_method ?? '') == 'nagad' ? 'checked' : '' }}>
                                <i class="fas fa-mobile-alt" style="color: #ED1C24;"></i>
                                <h6 class="fw-bold mb-0">Nagad</h6>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="payment-method-card {{ old('payment_method', $existingPayment->payment_method ?? '') == 'rocket' ? 'active' : '' }}" 
                                 onclick="selectPaymentMethod('rocket')">
                                <input type="radio" name="payment_method" value="rocket" id="method-rocket" class="d-none"
                                       {{ old('payment_method', $existingPayment->payment_method ?? '') == 'rocket' ? 'checked' : '' }}>
                                <i class="fas fa-mobile-alt" style="color: #8B3A8B;"></i>
                                <h6 class="fw-bold mb-0">Rocket</h6>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="payment-method-card {{ old('payment_method', $existingPayment->payment_method ?? '') == 'bank' ? 'active' : '' }}" 
                                 onclick="selectPaymentMethod('bank')">
                                <input type="radio" name="payment_method" value="bank" id="method-bank" class="d-none"
                                       {{ old('payment_method', $existingPayment->payment_method ?? '') == 'bank' ? 'checked' : '' }}>
                                <i class="fas fa-university" style="color: #0066CC;"></i>
                                <h6 class="fw-bold mb-0">Bank Transfer</h6>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="payment-method-card {{ old('payment_method', $existingPayment->payment_method ?? '') == 'cash' ? 'active' : '' }}" 
                                 onclick="selectPaymentMethod('cash')">
                                <input type="radio" name="payment_method" value="cash" id="method-cash" class="d-none"
                                       {{ old('payment_method', $existingPayment->payment_method ?? '') == 'cash' ? 'checked' : '' }}>
                                <i class="fas fa-money-bill-wave" style="color: #10b981;"></i>
                                <h6 class="fw-bold mb-0">Cash on Delivery</h6>
                            </div>
                        </div>
                    </div>

                    @error('payment_method')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <!-- Payment Details -->
                    <div id="payment-details" style="display: none;">
                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Payment Details</h5>

                        <!-- Sender Number -->
                        <div class="mb-3" id="sender-number-field">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone"></i> Your Mobile Number *
                            </label>
                            <input type="text" name="sender_number" 
                                   class="form-control @error('sender_number') is-invalid @enderror" 
                                   value="{{ old('sender_number', $existingPayment->sender_number ?? '') }}"
                                   placeholder="01XXXXXXXXX">
                            @error('sender_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter the number you used to send money</small>
                        </div>

                        <!-- Payment Proof -->
                        <div class="mb-4" id="payment-proof-field">
                            <label class="form-label fw-bold">
                                <i class="fas fa-camera"></i> Payment Screenshot *
                            </label>
                            <input type="file" name="payment_proof" accept="image/*"
                                   class="form-control @error('payment_proof') is-invalid @enderror">
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload screenshot of your payment confirmation (Max 2MB)</small>
                        </div>

                        <!-- Instructions -->
                        <div class="alert alert-info">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-info-circle"></i> Payment Instructions
                            </h6>
                            <ol class="mb-0" id="payment-instructions">
                                <li>Make payment of <strong>৳{{ number_format($auction->current_price, 2) }}</strong></li>
                                <li>Send to: <strong>01700000000</strong> (Admin Number)</li>
                                <li>Take a screenshot of the transaction</li>
                                <li>Upload the screenshot above</li>
                                <li>Submit and wait for admin approval</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check"></i> Submit Payment
                        </button>
                        <a href="{{ route('bidder.payments.index') }}" class="btn btn-outline-secondary">
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
    function selectPaymentMethod(method) {
        // Remove active class from all cards
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Add active class to selected card
        event.currentTarget.classList.add('active');
        
        // Check the radio button
        document.getElementById('method-' + method).checked = true;

        // Show/hide payment details based on method
        const paymentDetails = document.getElementById('payment-details');
        const senderField = document.getElementById('sender-number-field');
        const proofField = document.getElementById('payment-proof-field');
        const instructions = document.getElementById('payment-instructions');

        if (method === 'cash') {
            paymentDetails.style.display = 'none';
        } else {
            paymentDetails.style.display = 'block';
            
            if (method === 'bank') {
                instructions.innerHTML = `
                    <li>Transfer amount: <strong>৳{{ number_format($auction->current_price, 2) }}</strong></li>
                    <li>Bank: Dutch Bangla Bank</li>
                    <li>Account Number: 1234567890</li>
                    <li>Account Name: Deshi Bid</li>
                    <li>Take screenshot of transaction</li>
                    <li>Upload screenshot and submit</li>
                `;
            } else {
                instructions.innerHTML = `
                    <li>Make payment of <strong>৳{{ number_format($auction->current_price, 2) }}</strong></li>
                    <li>Send to: <strong>01700000000</strong> (Admin Number)</li>
                    <li>Take a screenshot of the transaction</li>
                    <li>Upload the screenshot above</li>
                    <li>Submit and wait for admin approval</li>
                `;
            }
        }
    }

    // Show details on page load if method is selected
    document.addEventListener('DOMContentLoaded', function() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedMethod && selectedMethod.value !== 'cash') {
            document.getElementById('payment-details').style.display = 'block';
        }
    });
</script>
@endpush
@endsection
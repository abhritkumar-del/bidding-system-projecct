@extends('layouts.app')

@section('title', 'Register - Deshi Bid')

@section('content')
<style>
    .register-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .role-card {
        background: white;
        border: 3px solid #e5e7eb;
        border-radius: 15px;
        padding: 2rem;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .role-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .role-card.active {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }

    .role-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
</style>

<div class="register-container">
    <div class="card">
        <div class="card-body p-5">
            <h2 class="text-center mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fas fa-user-plus"></i> Create Account
            </h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           placeholder="Enter your full name">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           placeholder="your@email.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="text" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           required
                           placeholder="01XXXXXXXXX">
                    @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required
                           placeholder="Minimum 8 characters">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           name="password_confirmation" 
                           required
                           placeholder="Re-enter your password">
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-user-tag"></i> I want to:
                    </label>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="role-card" onclick="selectRole('seller')">
                                <input type="radio" name="role" value="seller" id="role-seller" class="d-none" {{ old('role') == 'seller' ? 'checked' : '' }}>
                                <i class="fas fa-store"></i>
                                <h5 class="fw-bold mb-2">Sell Products</h5>
                                <p class="text-muted small mb-0">List and auction your items</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="role-card" onclick="selectRole('bidder')">
                                <input type="radio" name="role" value="bidder" id="role-bidder" class="d-none" {{ old('role') == 'bidder' ? 'checked' : '' }}>
                                <i class="fas fa-gavel"></i>
                                <h5 class="fw-bold mb-2">Place Bids</h5>
                                <p class="text-muted small mb-0">Bid on exciting auctions</p>
                            </div>
                        </div>
                    </div>
                    
                    @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>

                <div class="text-center mt-4">
                    <p class="mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="fw-bold" style="color: #667eea;">
                            Login here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectRole(role) {
        // Remove active class from all cards
        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Add active class to selected card
        event.currentTarget.classList.add('active');
        
        // Check the radio button
        document.getElementById('role-' + role).checked = true;
    }

    // Set active class on page load if role is selected
    document.addEventListener('DOMContentLoaded', function() {
        const selectedRole = document.querySelector('input[name="role"]:checked');
        if (selectedRole) {
            const card = document.querySelector(`#role-${selectedRole.value}`).closest('.role-card');
            card.classList.add('active');
        }
    });
</script>
@endpush
@endsection
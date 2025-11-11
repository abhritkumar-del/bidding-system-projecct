@extends('layouts.app')

@section('title', 'Login - Deshi Bid')

@section('content')
<style>
    .login-container {
        max-width: 500px;
        margin: 0 auto;
    }
</style>

<div class="login-container">
    <div class="card">
        <div class="card-body p-5">
            <h2 class="text-center mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fas fa-sign-in-alt"></i> Welcome Back
            </h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="your@email.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required
                           placeholder="Enter your password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted small">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <hr>

                <div class="text-center">
                    <p class="mb-0">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="fw-bold" style="color: #667eea;">
                            Register here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Demo Credentials Info -->
    <div class="alert alert-info mt-4">
        <strong><i class="fas fa-info-circle"></i> Demo Credentials:</strong><br>
        <small>
            <strong>Admin:</strong> admin@deshibid.com / admin123<br>
            <strong>Seller:</strong> seller@deshibid.com / seller123<br>
            <strong>Bidder:</strong> bidder@deshibid.com / bidder123
        </small>
    </div>
</div>
@endsection
<div class="auth-card">
    <div class="auth-header">
        <div class="logo">
            <i class="fas fa-user-shield"></i>
        </div>
        <h1>Admin Login</h1>
        <p>Sign in to access the admin dashboard</p>
    </div>

    {{-- Display Success/Error Messages --}}
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <form wire:submit="login">
        {{-- Email Field --}}
        <div class="form-floating">
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   placeholder="name@example.com"
                   wire:model="email"
                   required>
            <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password Field --}}
        <div class="form-floating">
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   placeholder="Password"
                   wire:model="password"
                   required>
            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="form-check mb-3">
            <input class="form-check-input" 
                   type="checkbox" 
                   id="remember"
                   wire:model="remember">
            <label class="form-check-label" for="remember">
                Remember me
            </label>
        </div>

        {{-- Submit Button --}}
        <button type="submit" 
                class="btn btn-primary btn-login w-100"
                wire:loading.attr="disabled">
            <span wire:loading.remove>
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin me-2"></i>Signing in...
            </span>
        </button>
    </form>

    <div class="text-center mt-4">
        <small class="text-muted">
            <i class="fas fa-shield-alt me-1"></i>
            Secure admin access only
        </small>
    </div>
</div>

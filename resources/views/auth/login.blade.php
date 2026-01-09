@extends('admin.layouts.auth')

@section('content')
    <div class="text-center mb-4">
        <h3 class="fw-bold mb-1">Admin Login</h3>
        <p class="text-muted mb-0">Sign in to continue</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation Errors (top) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
            >
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            >
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember me + Forgot password --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Log in
        </button>

        <div class="text-center mt-3">
            <span class="text-muted">No account?</span>
            <a href="{{ route('register') }}" class="text-decoration-none">Create one</a>
        </div>
    </form>
@endsection

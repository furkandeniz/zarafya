@extends('admin.layouts.auth')

@section('content')
    <div class="text-center mb-4">
        <h3 class="fw-bold mb-1">Admin Login</h3>
        <p class="text-muted mb-0">Sign in to continue</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

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

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                required
                autocomplete="current-password"
            >
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <a class="text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100">Sign In</button>

        <div class="text-center mt-3">
            <span class="text-muted">No account?</span>
            <a href="{{ route('register') }}" class="text-decoration-none">Create one</a>
        </div>
    </form>
@endsection

@extends('app.layouts.guest')

@section('title', 'Giriş Yap')

@section('content')
    <h3 class="mb-1 fw-bold">Giriş Yap</h3>
    <p class="text-muted mb-4">Hesabına giriş yap.</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- Validation Errors (Top) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('app.login.store') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
                autofocus
                autocomplete="username"
                placeholder="ornek@mail.com"
            >
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
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

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Beni hatırla</label>
            </div>

            <a class="text-decoration-none" href="{{ route('app.password.request') }}">Şifremi unuttum</a>
        </div>

        <button type="submit" class="btn btn-success w-100">
            Giriş Yap
        </button>

        <div class="text-center mt-3">
            <span class="text-muted">Hesabın yok mu?</span>
            <a class="text-decoration-none" href="{{ route('app.register') }}">Kayıt ol</a>
        </div>
@endsection

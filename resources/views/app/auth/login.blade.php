@extends('app.layouts.guest')

@section('title', 'Giriş Yap')

@section('content')

    <h1 class="auth-title">Tekrar Hoş Geldiniz</h1>
    <p class="auth-subtitle">Hesabınıza giriş yapın.</p>

    @if (session('status'))
        <div class="auth-alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="auth-alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('app.login.store') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="ornek@zarafya.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Beni hatırla</label>
            </div>
            <a href="{{ route('app.password.request') }}" class="auth-link">Şifremi unuttum</a>
        </div>

        <button type="submit" class="btn-auth-primary">Giriş Yap</button>
    </form>

    <p class="auth-footer-text">
        Hesabınız yok mu?
        <a href="{{ route('app.register') }}" class="auth-link">Kayıt olun</a>
    </p>

@endsection

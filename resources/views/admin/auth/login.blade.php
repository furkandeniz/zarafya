@extends('admin.layouts.auth')

@section('content')

    <h1 class="auth-form-title">Giriş Yap</h1>
    <p class="auth-form-subtitle">Devam etmek için hesabınıza giriş yapın.</p>

    @if ($errors->any())
        <div class="auth-alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('status'))
        <div class="auth-alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">E-posta</label>
            <input type="email" id="email" name="email"
                   class="form-control"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="ornek@zarafya.com">
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Şifre</label>
            <input type="password" id="password" name="password"
                   class="form-control"
                   required autocomplete="current-password"
                   placeholder="••••••••">
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Beni hatırla</label>
            </div>
            <a href="{{ route('password.request') }}" class="auth-link">Şifremi unuttum</a>
        </div>

        <button type="submit" class="btn-auth">Giriş Yap</button>
    </form>

@endsection

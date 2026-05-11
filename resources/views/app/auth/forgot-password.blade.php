@extends('app.layouts.guest')

@section('title', 'Şifremi Unuttum')

@section('content')

    <h1 class="auth-title">Şifre Sıfırla</h1>
    <p class="auth-subtitle">
        E-posta adresinizi girin,<br>sıfırlama bağlantısını gönderelim.
    </p>

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

    <form method="POST" action="{{ route('app.password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">E-posta</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   required autofocus
                   placeholder="ornek@zarafya.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-auth-primary">Sıfırlama Bağlantısı Gönder</button>
    </form>

    <p class="auth-footer-text">
        <a href="{{ route('app.login') }}" class="auth-link">← Giriş sayfasına dön</a>
    </p>

@endsection

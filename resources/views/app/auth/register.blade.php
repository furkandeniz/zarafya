@extends('app.layouts.guest')

@section('title', 'Kayıt Ol')

@section('content')

    <h1 class="auth-title">Hesap Oluşturun</h1>
    <p class="auth-subtitle">Zarafya ailesine katılın.</p>

    @if ($errors->any())
        <div class="auth-alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('app.register.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Ad Soyad</label>
            <input id="name" type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   placeholder="Ad Soyad">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   placeholder="ornek@zarafya.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text" style="font-size:12px;color:#888;margin-top:4px;">En az 8 karakter.</div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Şifre (Tekrar)</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control"
                   required autocomplete="new-password"
                   placeholder="••••••••">
        </div>

        <button type="submit" class="btn-auth-primary">Kayıt Ol</button>
    </form>

    <p class="auth-footer-text">
        Zaten hesabınız var mı?
        <a href="{{ route('app.login') }}" class="auth-link">Giriş yapın</a>
    </p>

@endsection

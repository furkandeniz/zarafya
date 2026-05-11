@extends('admin.layouts.auth')

@section('content')

    <a href="{{ route('login') }}" class="auth-link d-inline-flex align-items-center gap-1 mb-4" style="font-size:13px;">
        ← Giriş sayfasına dön
    </a>

    <h1 class="auth-form-title">Şifre Sıfırla</h1>
    <p class="auth-form-subtitle">
        E-posta adresinizi girin, sıfırlama bağlantısını hemen gönderelim.
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

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label" for="email">E-posta</label>
            <input type="email" id="email" name="email"
                   class="form-control"
                   value="{{ old('email') }}"
                   required autofocus
                   placeholder="ornek@zarafya.com">
        </div>

        <button type="submit" class="btn-auth">Sıfırlama Bağlantısı Gönder</button>
    </form>

@endsection

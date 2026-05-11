@extends('app.layouts.guest')

@section('title', 'Kayıt Ol')

@section('content')
    <h3 class="mb-1 fw-bold">Kayıt Ol</h3>
    <p class="text-muted mb-4">Yeni hesap oluştur.</p>

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

    <form method="POST" action="{{ route('app.register.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Ad Soyad</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                autofocus
                autocomplete="name"
                placeholder="Ad Soyad"
            >
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
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
                autocomplete="new-password"
                placeholder="••••••••"
            >
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">En az 8 karakter.</div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Şifre (Tekrar)</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control"
                required
                autocomplete="new-password"
                placeholder="••••••••"
            >
        </div>

        <button type="submit" class="btn btn-success w-100">
            Kayıt Ol
        </button>

        <div class="text-center mt-3">
            <span class="text-muted">Zaten hesabın var mı?</span>
            <a class="text-decoration-none" href="{{ route('app.login') }}">Giriş yap</a>
        </div>
@endsection

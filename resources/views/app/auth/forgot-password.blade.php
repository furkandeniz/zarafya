@extends('app.layouts.guest')

@section('title', 'Şifremi Unuttum')

@section('content')
    <h3 class="mb-1 fw-bold">Şifremi Unuttum</h3>
    <p class="text-muted mb-4">E-posta adresinizi girin, şifre sıfırlama bağlantısı gönderelim.</p>

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

    <form method="POST" action="{{ route('app.password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autofocus placeholder="ornek@mail.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success w-100">Bağlantı Gönder</button>

        <div class="text-center mt-3">
            <a class="text-decoration-none" href="{{ route('app.login') }}">← Giriş sayfasına dön</a>
        </div>
    </form>
@endsection

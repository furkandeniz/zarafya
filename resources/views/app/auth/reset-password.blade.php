@extends('app.layouts.guest')

@section('title', 'Şifre Sıfırla')

@section('content')
    <h3 class="mb-1 fw-bold">Şifre Sıfırla</h3>
    <p class="text-muted mb-4">Yeni şifrenizi belirleyin.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('app.password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email', $request->email) }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required placeholder="ornek@mail.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Yeni Şifre</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Şifre (Tekrar)</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-auth-primary">Şifremi Güncelle</button>
    </form>
@endsection

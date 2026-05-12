@extends('app.account._layout')

@php $pageTitle = 'Şifre Güncelle'; @endphp

@section('account-content')

<div class="account-card">
    <div class="account-card-title">Şifre Güncelle</div>

    @if (session('success'))
        <div class="alert-account-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-account-danger">
            @foreach ($errors->all() as $error)
                <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('app.account.password.update') }}" class="account-form">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="current_password" class="form-label">Mevcut Şifre</label>
            <input id="current_password" type="password" name="current_password"
                   class="form-control @error('current_password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Yeni Şifre</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-5">
            <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control"
                   required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-account-primary">
            <i class="fas fa-lock me-2"></i>Şifreyi Güncelle
        </button>
    </form>
</div>

@endsection

@extends('app.account._layout')

@php $pageTitle = 'Bilgilerimi Güncelle'; @endphp

@section('account-content')

<div class="account-card">
    <div class="account-card-title">Bilgilerimi Güncelle</div>

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

    <form method="POST" action="{{ route('app.account.profile.update') }}" class="account-form">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="name" class="form-label">Ad Soyad</label>
            <input id="name" type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}"
                   required autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-5">
            <label for="email" class="form-label">E-posta</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-account-primary">
            <i class="fas fa-save me-2"></i>Kaydet
        </button>
    </form>
</div>

@endsection

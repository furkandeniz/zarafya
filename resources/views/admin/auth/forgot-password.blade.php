@extends('admin.layouts.auth')

@section('content')
    <div class="text-center mb-4">
        <h3 class="fw-bold mb-1">Forgot Password</h3>
        <p class="text-muted mb-0">We’ll email you a reset link</p>
    </div>

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

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a>
        </div>
    </form>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
    <style>
        /* Kaiadmin + Bootstrap üstünde sadece auth ekranı için küçük dokunuşlar */
        .auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-card {
            width: 100%;
            max-width: 440px;
        }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="card auth-card">
        <div class="card-body p-4">
            @yield('content')
        </div>
    </div>
</div>

@include('admin.partials.scripts')
</body>
</html>

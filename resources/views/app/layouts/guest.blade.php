<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Zarafya') — Zarafya</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f6f4; }

        .auth-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(47,47,47,0.09);
            padding: 44px 40px;
            width: 100%;
            max-width: 440px;
        }

        .auth-logo {
            display: block;
            margin: 0 auto 28px;
            height: 36px;
            width: auto;
        }

        .auth-divider {
            width: 36px;
            height: 3px;
            background: #f9bf29;
            border-radius: 2px;
            margin: 0 auto 26px;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 800;
            color: #2f2f2f;
            text-align: center;
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: #6a6a6a;
            text-align: center;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #2f2f2f;
            margin-bottom: 5px;
        }

        .form-control {
            border: 1.5px solid #dde3dd;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 14px;
            color: #2f2f2f;
            font-family: 'Inter', sans-serif;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: #3b5d50;
            box-shadow: 0 0 0 3px rgba(59,93,80,0.1);
            outline: none;
        }
        .form-control.is-invalid { border-color: #dc3545; }

        .btn-auth-primary {
            background: #3b5d50;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            width: 100%;
            transition: background .15s;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .btn-auth-primary:hover { background: #314d43; color: #fff; }

        .auth-link {
            color: #3b5d50;
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
        }
        .auth-link:hover { color: #314d43; text-decoration: underline; }

        .auth-footer-text {
            font-size: 13px;
            color: #6a6a6a;
            text-align: center;
            margin-top: 20px;
        }

        .auth-alert-danger {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #c0392b;
            margin-bottom: 18px;
        }
        .auth-alert-success {
            background: #f0faf4;
            border: 1px solid #b2dfcc;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #1e7145;
            margin-bottom: 18px;
        }

        .form-check-input:checked {
            background-color: #3b5d50;
            border-color: #3b5d50;
        }
        .form-check-label { font-size: 13px; color: #6a6a6a; }

        @media (max-width: 480px) {
            .auth-card { padding: 32px 22px; }
        }
    </style>
</head>
<body>

<div class="auth-section">
    <div style="width:100%;max-width:440px;">

        <div class="auth-card">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/zarafya_logo_black.png') }}" alt="Zarafya" class="auth-logo">
            </a>
            <div class="auth-divider"></div>

            @yield('content')
        </div>

        <p class="text-center mt-3" style="font-size:12px;color:#aaa;">
            &copy; {{ date('Y') }} Zarafya. Tüm hakları saklıdır.
        </p>
    </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

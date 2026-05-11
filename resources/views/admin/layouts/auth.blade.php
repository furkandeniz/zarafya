<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarafya — Yönetim Paneli</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
        }

        /* ── Sol: Marka Paneli ───────────────────────────────────── */
        .auth-brand {
            width: 400px;
            min-width: 400px;
            background: #3b5d50;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 44px 40px;
            position: relative;
            overflow: hidden;
        }

        /* Dekoratif daireler */
        .auth-brand::before {
            content: '';
            position: absolute;
            top: -90px; right: -90px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .auth-brand::after {
            content: '';
            position: absolute;
            bottom: -70px; left: -70px;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .auth-brand-logo img {
            height: 44px;
            width: auto;
        }

        .auth-brand-mid {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 0 32px;
            position: relative;
            z-index: 1;
        }

        .auth-brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(249,191,41,0.18);
            color: #f9bf29;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 100px;
            margin-bottom: 24px;
            width: fit-content;
        }

        .auth-brand-title {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -0.8px;
            margin-bottom: 14px;
        }

        .auth-brand-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            line-height: 1.75;
            max-width: 260px;
        }

        .auth-brand-divider {
            width: 40px;
            height: 3px;
            background: #f9bf29;
            border-radius: 2px;
            margin: 24px 0;
        }

        .auth-brand-stats {
            display: flex;
            gap: 28px;
        }
        .auth-stat-label {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .auth-stat-val {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .auth-brand-footer {
            font-size: 12px;
            color: rgba(255,255,255,0.28);
            position: relative;
            z-index: 1;
        }

        /* ── Sağ: Form Paneli ────────────────────────────────────── */
        .auth-form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f4;
            padding: 40px 24px;
        }

        .auth-form-box {
            width: 100%;
            max-width: 380px;
        }

        .auth-form-title {
            font-size: 26px;
            font-weight: 800;
            color: #2f2f2f;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .auth-form-subtitle {
            font-size: 14px;
            color: #6a6a6a;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #2f2f2f;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            border: 1.5px solid #dde3dd;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            color: #2f2f2f;
            background: #fff;
            width: 100%;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            border-color: #3b5d50;
            box-shadow: 0 0 0 3px rgba(59,93,80,0.1);
        }

        .btn-auth {
            background: #3b5d50;
            border: none;
            border-radius: 8px;
            padding: 13px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            width: 100%;
            transition: background .15s, transform .1s;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.2px;
        }
        .btn-auth:hover  { background: #314d43; }
        .btn-auth:active { transform: scale(.99); }

        .auth-link {
            color: #3b5d50;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: color .15s;
        }
        .auth-link:hover { color: #314d43; text-decoration: underline; }

        .form-check-input:checked {
            background-color: #3b5d50;
            border-color: #3b5d50;
        }
        .form-check-label { font-size: 13px; color: #6a6a6a; }

        .auth-alert-danger {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 13px;
            color: #c0392b;
            margin-bottom: 20px;
        }
        .auth-alert-success {
            background: #f0faf4;
            border: 1px solid #b2dfcc;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 13px;
            color: #1e7145;
            margin-bottom: 20px;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 820px) {
            body { flex-direction: column; }
            .auth-brand {
                width: 100%; min-width: 0;
                padding: 28px 24px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
            .auth-brand::before, .auth-brand::after { display: none; }
            .auth-brand-mid, .auth-brand-footer { display: none; }
        }
    </style>
</head>
<body>

{{-- Sol: Marka --}}
<div class="auth-brand">
    <div class="auth-brand-logo">
        <img src="{{ asset('images/zarafya_logo_white.png') }}" alt="Zarafya">
    </div>

    <div class="auth-brand-mid">
        <span class="auth-brand-tag">Yönetim Paneli</span>
        <h2 class="auth-brand-title">Zarafya'ya<br>Hoş Geldiniz</h2>
        <p class="auth-brand-desc">
            Ürünlerinizi, siparişlerinizi ve mağazanızı tek bir yerden kolayca yönetin.
        </p>
        <div class="auth-brand-divider"></div>
        <div class="auth-brand-stats">
            <div>
                <div class="auth-stat-label">Platform</div>
                <div class="auth-stat-val">Zarafya</div>
            </div>
            <div>
                <div class="auth-stat-label">Versiyon</div>
                <div class="auth-stat-val">2.0</div>
            </div>
        </div>
    </div>

    <div class="auth-brand-footer">
        &copy; {{ date('Y') }} Zarafya. Tüm hakları saklıdır.
    </div>
</div>

{{-- Sağ: Form --}}
<div class="auth-form-panel">
    <div class="auth-form-box">
        @yield('content')
    </div>
</div>

@include('admin.partials.scripts')
</body>
</html>

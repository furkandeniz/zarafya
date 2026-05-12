@extends('app.layouts.main')

@section('title', $pageTitle ?? 'Hesabım')
@section('robots', 'noindex, nofollow')

@push('styles')
<style>
    .account-section {
        padding: 60px 0 80px;
        min-height: calc(100vh - 200px);
        background: #f4f6f4;
    }

    .account-sidebar {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(47,47,47,0.07);
        padding: 28px 20px;
        position: sticky;
        top: 24px;
    }

    .account-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eef1ee;
        margin-bottom: 20px;
    }

    .account-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #3b5d50;
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .account-user-name {
        font-size: 14px;
        font-weight: 700;
        color: #2f2f2f;
        line-height: 1.3;
    }

    .account-user-email {
        font-size: 12px;
        color: #8a8a8a;
        word-break: break-all;
    }

    .account-nav {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .account-nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #4a4a4a;
        text-decoration: none;
        transition: background .15s, color .15s;
        background: none;
        border: none;
        cursor: pointer;
    }

    .account-nav-item i {
        width: 16px;
        text-align: center;
        color: #8a8a8a;
        font-size: 13px;
        flex-shrink: 0;
    }

    .account-nav-item:hover {
        background: #f0f4f2;
        color: #3b5d50;
        text-decoration: none;
    }

    .account-nav-item:hover i { color: #3b5d50; }

    .account-nav-item.active {
        background: #edf2f0;
        color: #3b5d50;
        font-weight: 600;
    }

    .account-nav-item.active i { color: #3b5d50; }

    .account-nav-logout {
        color: #c0392b;
        margin-top: 4px;
    }

    .account-nav-logout i { color: #c0392b; }
    .account-nav-logout:hover { background: #fff0f0; color: #a93226; }

    .account-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(47,47,47,0.07);
        padding: 32px 28px;
    }

    .account-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #2f2f2f;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #eef1ee;
        letter-spacing: -0.3px;
    }

    .account-form .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #2f2f2f;
    }

    .account-form .form-control {
        border: 1.5px solid #dde3dd;
        border-radius: 8px;
        padding: 10px 13px;
        font-size: 14px;
        color: #2f2f2f;
    }

    .account-form .form-control:focus {
        border-color: #3b5d50;
        box-shadow: 0 0 0 3px rgba(59,93,80,0.1);
    }

    .btn-account-primary {
        background: #3b5d50;
        border: none;
        border-radius: 8px;
        padding: 11px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        transition: background .15s;
        cursor: pointer;
    }

    .btn-account-primary:hover { background: #314d43; color: #fff; }

    .order-status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .alert-account-success {
        background: #f0faf4;
        border: 1px solid #b2dfcc;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        color: #1e7145;
        margin-bottom: 20px;
    }

    .alert-account-danger {
        background: #fff0f0;
        border: 1px solid #f5c6c6;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        color: #c0392b;
        margin-bottom: 20px;
    }

    @media (max-width: 991px) {
        .account-sidebar { position: static; margin-bottom: 24px; }
    }
</style>
@endpush

@section('content')
<section class="account-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('app.account._sidebar')
            </div>
            <div class="col-lg-9">
                @yield('account-content')
            </div>
        </div>
    </div>
</section>
@endsection

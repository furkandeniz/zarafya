<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Zarafya')</title>

    {{-- App theme CSS --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

{{-- İstersen burada app header’ını include edebilirsin --}}
{{-- @include('app.partials.header') --}}

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- App theme JS --}}
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

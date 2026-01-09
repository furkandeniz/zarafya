<!doctype html>
<html lang="en">
<head>
    @include('app.partials.head')
</head>

<body>

@include('app.partials.navbar')

<main>
    @yield('content')
</main>

@include('app.partials.footer')
@include('app.partials.scripts')

</body>
</html>

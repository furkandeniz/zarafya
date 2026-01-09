<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>
<body>
<div class="wrapper">

    @include('admin.partials.sidebar')

    <div class="main-panel">
        @include('admin.partials.navbar')

        <div class="container">
            @yield('content')
        </div>

        @include('admin.partials.footer')
    </div>

</div>

@include('admin.partials.scripts')
</body>
</html>

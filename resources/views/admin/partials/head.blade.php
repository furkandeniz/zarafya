<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Zarafya Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />

<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />

<script src="{{ asset('admin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
<script>
    WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["{{ asset('admin/assets/css/fonts.min.css') }}"],
        },
        active: function () {
            sessionStorage.fonts = true;
        },
    });
</script>

<link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/assets/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/assets/css/kaiadmin.min.css') }}" />

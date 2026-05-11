<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>{{ $title ?? '' }}</h1>
                    @if (!empty($subtitle))
                        <p class="mb-4">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hero-img-card">
                    <img src="{{ asset('images/' . ($image ?? 'banner-new.png')) }}" class="img-fluid" alt="Zarafya">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

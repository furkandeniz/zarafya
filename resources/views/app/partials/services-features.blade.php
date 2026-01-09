<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">

        <div class="row my-5">

            @php
                $features = [
                  ['icon' => 'images/truck.svg',   'title' => 'Fast & Free Shipping'],
                  ['icon' => 'images/bag.svg',     'title' => 'Easy to Shop'],
                  ['icon' => 'images/support.svg', 'title' => '24/7 Support'],
                  ['icon' => 'images/return.svg',  'title' => 'Hassle Free Returns'],
                  ['icon' => 'images/truck.svg',   'title' => 'Fast & Free Shipping'],
                  ['icon' => 'images/bag.svg',     'title' => 'Easy to Shop'],
                  ['icon' => 'images/support.svg', 'title' => '24/7 Support'],
                  ['icon' => 'images/return.svg',  'title' => 'Hassle Free Returns'],
                ];
            @endphp

            @foreach($features as $f)
                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset($f['icon']) }}" alt="{{ $f['title'] }}" class="img-fluid">
                        </div>
                        <h3>{!! $f['title'] !!}</h3>
                        <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</div>
<!-- End Why Choose Us Section -->

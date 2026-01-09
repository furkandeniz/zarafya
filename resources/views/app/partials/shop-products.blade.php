@php
    $products = [
      ['img' => 'images/product-3.png', 'title' => 'Nordic Chair',        'price' => '$50.00'],
      ['img' => 'images/product-1.png', 'title' => 'Nordic Chair',        'price' => '$50.00'],
      ['img' => 'images/product-2.png', 'title' => 'Kruzo Aero Chair',    'price' => '$78.00'],
      ['img' => 'images/product-3.png', 'title' => 'Ergonomic Chair',     'price' => '$43.00'],
      ['img' => 'images/product-3.png', 'title' => 'Nordic Chair',        'price' => '$50.00'],
      ['img' => 'images/product-1.png', 'title' => 'Nordic Chair',        'price' => '$50.00'],
      ['img' => 'images/product-2.png', 'title' => 'Kruzo Aero Chair',    'price' => '$78.00'],
      ['img' => 'images/product-3.png', 'title' => 'Ergonomic Chair',     'price' => '$43.00'],
    ];
@endphp

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            @foreach($products as $p)
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item" href="#">
                        <img src="{{ asset($p['img']) }}" class="img-fluid product-thumbnail" alt="{{ $p['title'] }}">
                        <h3 class="product-title">{{ $p['title'] }}</h3>
                        <strong class="product-price">{{ $p['price'] }}</strong>

                        <span class="icon-cross">
              <img src="{{ asset('images/cross.svg') }}" class="img-fluid" alt="Add to cart">
            </span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

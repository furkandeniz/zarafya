<!-- Start Blog Section -->
<div class="blog-section">
    <div class="container">
        <div class="row">

            @php
                $posts = [
                  ['img' => 'images/post-1.jpg', 'title' => 'First Time Home Owner Ideas',         'author' => 'Kristin Watson', 'date' => 'Dec 19, 2021'],
                  ['img' => 'images/post-2.jpg', 'title' => 'How To Keep Your Furniture Clean',    'author' => 'Robert Fox',     'date' => 'Dec 15, 2021'],
                  ['img' => 'images/post-3.jpg', 'title' => 'Small Space Furniture Apartment Ideas','author' => 'Kristin Watson','date' => 'Dec 12, 2021'],
                  ['img' => 'images/post-1.jpg', 'title' => 'First Time Home Owner Ideas',         'author' => 'Kristin Watson', 'date' => 'Dec 19, 2021'],
                  ['img' => 'images/post-2.jpg', 'title' => 'How To Keep Your Furniture Clean',    'author' => 'Robert Fox',     'date' => 'Dec 15, 2021'],
                  ['img' => 'images/post-3.jpg', 'title' => 'Small Space Furniture Apartment Ideas','author' => 'Kristin Watson','date' => 'Dec 12, 2021'],
                  ['img' => 'images/post-1.jpg', 'title' => 'First Time Home Owner Ideas',         'author' => 'Kristin Watson', 'date' => 'Dec 19, 2021'],
                  ['img' => 'images/post-2.jpg', 'title' => 'How To Keep Your Furniture Clean',    'author' => 'Robert Fox',     'date' => 'Dec 15, 2021'],
                  ['img' => 'images/post-3.jpg', 'title' => 'Small Space Furniture Apartment Ideas','author' => 'Kristin Watson','date' => 'Dec 12, 2021'],
                ];
            @endphp

            @foreach($posts as $post)
                <div class="col-12 col-sm-6 col-md-4 mb-5">
                    <div class="post-entry">
                        <a href="#" class="post-thumbnail">
                            <img src="{{ asset($post['img']) }}" alt="{{ $post['title'] }}" class="img-fluid">
                        </a>
                        <div class="post-content-entry">
                            <h3><a href="#">{{ $post['title'] }}</a></h3>
                            <div class="meta">
                                <span>by <a href="#">{{ $post['author'] }}</a></span>
                                <span>on <a href="#">{{ $post['date'] }}</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

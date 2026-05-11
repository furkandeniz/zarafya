<!-- Blog Yazıları -->
<div class="blog-list-section">
    <div class="container">

        @if($posts->count())

            @php
                $all      = $posts->values();
                $featured = $all->get(0);
                $side     = $all->slice(1, 2);
                $rest     = $all->slice(3);
            @endphp

            {{-- Üst blok: büyük hero + 2 yan kart --}}
            <div class="row g-4 mb-4">

                {{-- Büyük hero kart --}}
                <div class="col-lg-7">
                    <a href="{{ route('blog.show', $featured->slug) }}" class="blog-hero-card">
                        <div class="blog-hero-card__img-wrap">
                            @if($featured->image)
                                <img src="{{ Storage::url($featured->image) }}" alt="{{ $featured->title }}" class="blog-hero-card__img">
                            @else
                                <div class="blog-hero-card__no-img"><i class="fas fa-pen-nib"></i></div>
                            @endif
                        </div>
                        <div class="blog-hero-card__overlay">
                            <div class="blog-hero-card__body">
                                <div class="blog-hero-card__meta">
                                    <span><i class="fas fa-calendar-alt me-1"></i>{{ $featured->published_at->format('d M Y') }}</span>
                                    <span class="mx-2">·</span>
                                    <span>{{ $featured->author->name ?? 'Zarafya Editör' }}</span>
                                </div>
                                <h2 class="blog-hero-card__title">{{ $featured->title }}</h2>
                                @if($featured->excerpt)
                                    <p class="blog-hero-card__excerpt">{{ Str::limit($featured->excerpt, 120) }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Yan 2 kart --}}
                <div class="col-lg-5 d-flex flex-column gap-4">
                    @foreach($side as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-side-card">
                            <div class="blog-side-card__img-wrap">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="blog-side-card__img">
                                @else
                                    <div class="blog-side-card__no-img"><i class="fas fa-pen-nib"></i></div>
                                @endif
                            </div>
                            <div class="blog-side-card__body">
                                <h3 class="blog-side-card__title">{{ $post->title }}</h3>
                                @if($post->excerpt)
                                    <p class="blog-side-card__excerpt">{{ Str::limit($post->excerpt, 80) }}</p>
                                @endif
                                <div class="blog-side-card__meta">
                                    {{ $post->published_at->format('d.m.Y') }} · {{ $post->author->name ?? 'Zarafya Editör' }}
                                </div>
                            </div>
                        </a>
                    @endforeach

                    {{-- Yan kart sayısı 1 ise boşluğu doldur --}}
                    @if($side->count() < 2)
                        <div class="flex-fill"></div>
                    @endif
                </div>

            </div>

            {{-- Alt grid: kalan yazılar --}}
            @if($rest->count())
                <div class="row g-4 mb-5">
                    @foreach($rest as $post)
                        <div class="col-12 col-sm-6 col-lg-4 d-flex">
                            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card w-100">
                                <div class="blog-card__img-wrap">
                                    @if($post->image)
                                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="blog-card__img">
                                    @else
                                        <div class="blog-card__img-placeholder"><i class="fas fa-pen-nib"></i></div>
                                    @endif
                                </div>
                                <div class="blog-card__body">
                                    <h3 class="blog-card__title">{{ $post->title }}</h3>
                                    @if($post->excerpt)
                                        <p class="blog-card__excerpt">{{ Str::limit($post->excerpt, 90) }}</p>
                                    @endif
                                    <div class="blog-card__meta">
                                        <span>{{ $post->author->name ?? 'Zarafya Editör' }}</span>
                                        <span class="mx-1">·</span>
                                        <span>{{ $post->published_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Sayfalama --}}
            @if($posts->hasPages())
                <div class="d-flex justify-content-center pb-4">
                    {{ $posts->links() }}
                </div>
            @endif

        @else
            <div class="blog-empty">
                <i class="fas fa-pen-nib blog-empty__icon"></i>
                <h4>Henüz blog yazısı yok</h4>
                <p>Yakında burada ilham veren içerikler yayınlanacak.</p>
            </div>
        @endif

    </div>
</div>
<!-- End Blog Yazıları -->

@extends('app.layouts.main')

@php
    $blogSeoDesc  = Str::limit(strip_tags($blog->excerpt ?: $blog->content), 155);
    $blogSeoImage = $blog->image ? Storage::url($blog->image) : asset('images/og-default.jpg');
@endphp

@section('seo_title', $blog->title)
@section('seo_description', $blogSeoDesc)
@section('og_type', 'article')
@section('og_image', $blogSeoImage)

@push('jsonld')
@php
$_jsonLd = [
    '@context'      => 'https://schema.org',
    '@type'         => 'Article',
    'headline'      => $blog->title,
    'description'   => $blogSeoDesc,
    'url'           => url()->current(),
    'datePublished' => $blog->published_at->toIso8601String(),
    'dateModified'  => $blog->updated_at->toIso8601String(),
    'author'        => ['@type' => 'Person', 'name' => $blog->author->name ?? 'Zarafya Editör'],
    'publisher'     => [
        '@type' => 'Organization',
        'name'  => 'Zarafya',
        'logo'  => ['@type' => 'ImageObject', 'url' => asset('images/zarafya_logo_white.png')],
    ],
];
if ($blog->image) {
    $_jsonLd['image'] = $blogSeoImage;
}
@endphp
<script type="application/ld+json">{!! json_encode($_jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
    @include('app.partials.page-hero', ['title' => $blog->title, 'subtitle' => $blog->excerpt ?? '', 'image' => 'blog.png'])

    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    @if($blog->image)
                        <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}"
                             class="img-fluid rounded mb-4" style="width:100%;max-height:420px;object-fit:cover;">
                    @endif

                    <div class="d-flex align-items-center gap-3 mb-4 text-muted small">
                        <span><i class="fas fa-user me-1"></i>{{ $blog->author->name ?? 'Zarafya Editör' }}</span>
                        <span><i class="fas fa-calendar me-1"></i>{{ $blog->published_at->format('d F Y') }}</span>
                    </div>

                    <div class="blog-content" style="line-height:1.9;font-size:1.05rem;">
                        {!! $blog->content !!}
                    </div>

                    <hr class="my-5">

                    <a href="{{ route('blog') }}" class="btn btn-outline-secondary btn-round">
                        <i class="fas fa-arrow-left me-1"></i> Tüm Yazılar
                    </a>

                </div>
            </div>

            @if($related->count())
            <div class="row mt-5 mb-4">
                <div class="col-12 mb-4">
                    <h3 class="section-title">Diğer Yazılar</h3>
                </div>
                @foreach($related as $post)
                    <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
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
                                    <p class="blog-card__excerpt">{{ Str::limit($post->excerpt, 80) }}</p>
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

        </div>
    </div>
@endsection

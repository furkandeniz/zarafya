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
                        @if(\App\Models\Setting::get('blog_show_visit_count'))
                            <span><i class="fas fa-eye me-1"></i>{{ number_format($blog->visit_count, 0, ',', '.') }} görüntülenme</span>
                        @endif
                    </div>

                    <div class="blog-content" style="line-height:1.9;font-size:1.05rem;">
                        {!! $blog->content !!}
                    </div>

                    <hr class="my-5">

                    <a href="{{ route('blog') }}" class="btn btn-outline-secondary btn-round">
                        <i class="fas fa-arrow-left me-1"></i> Tüm Yazılar
                    </a>

                    {{-- Yorumlar --}}
                    <div class="blog-comments mt-5">

                        <h4 class="blog-comments__title">
                            <i class="fas fa-comments me-2"></i>Yorumlar
                            @if($comments->count())
                                <span class="badge bg-secondary ms-1" style="font-size:.75rem;">{{ $comments->count() }}</span>
                            @endif
                        </h4>

                        @if($comments->count())
                            <div class="blog-comments__list mt-4">
                                @foreach($comments as $comment)
                                    <div class="blog-comment-item">
                                        <div class="blog-comment-item__avatar">
                                            {{ strtoupper(substr($comment->name, 0, 1)) }}
                                        </div>
                                        <div class="blog-comment-item__body">
                                            <div class="blog-comment-item__header">
                                                <span class="blog-comment-item__name">{{ $comment->masked_name }}</span>
                                                <span class="blog-comment-item__date">{{ $comment->approved_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="blog-comment-item__text">{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mt-3">Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                        @endif

                        {{-- Yorum Formu --}}
                        <div class="blog-comment-form mt-5">
                            <h5 class="mb-4">Yorum Yap</h5>

                            @if(session('comment_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-1"></i> {{ session('comment_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('blog.comment.store', $blog->slug) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">İsim <span class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}" placeholder="Adınız Soyadınız">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">E-posta <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}" placeholder="ornek@mail.com">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Yorumunuz <span class="text-danger">*</span></label>
                                        <textarea name="body" rows="4"
                                                  class="form-control @error('body') is-invalid @enderror"
                                                  placeholder="Düşüncelerinizi paylaşın...">{{ old('body') }}</textarea>
                                        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-round">
                                            <i class="fas fa-paper-plane me-1"></i> Yorum Gönder
                                        </button>
                                        <span class="text-muted small ms-2">Yorumunuz admin onayından sonra yayınlanır.</span>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

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

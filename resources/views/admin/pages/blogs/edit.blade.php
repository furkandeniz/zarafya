@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Blog Yazısını Düzenle</h3>
            <h6 class="op-7 mb-2">
                <a href="{{ route('admin.blogs.index') }}" class="text-muted">Blog Yazıları</a>
                &rsaquo; #{{ $blog->id }}
            </h6>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-round">
            <i class="fa fa-arrow-left me-1"></i> Geri Dön
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Güncelleme formu --}}
    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf @method('PUT')

        <div class="row g-3">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">İçerik</h4></div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Başlık <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Özet</label>
                            <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">İçerik <span class="text-danger">*</span></label>
                            <textarea name="content" id="blogContent" class="form-control">{{ old('content', $blog->content) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header"><h4 class="card-title mb-0">Yayın Ayarları</h4></div>
                    <div class="card-body">

                        <div class="mb-2 text-muted small">
                            <i class="fas fa-clock me-1"></i>
                            Oluşturulma: {{ $blog->created_at->format('d.m.Y H:i') }}
                        </div>
                        @if($blog->published_at)
                        <div class="mb-3 text-muted small">
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            Yayın tarihi: {{ $blog->published_at->format('d.m.Y H:i') }}
                        </div>
                        @endif

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1"
                                {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isPublished">Yayında</label>
                            <div class="text-muted small">Kapalıysa taslak olarak kaydedilir.</div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-round">
                                <i class="fas fa-save me-1"></i> Kaydet
                            </button>
                        </div>

                        <hr>

                        {{-- Sil butonu: ana formun dışında, form attribute ile bağlı --}}
                        <button type="submit" form="deleteForm" class="btn btn-outline-danger btn-round w-100">
                            <i class="fas fa-trash me-1"></i> Yazıyı Sil
                        </button>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Kapak Görseli</h4></div>
                    <div class="card-body">
                        @if($blog->image)
                            <img id="coverPreview" src="{{ Storage::url($blog->image) }}" alt="Kapak"
                                 class="img-fluid rounded mb-3" style="max-height:200px;width:100%;object-fit:cover;">
                        @else
                            <img id="coverPreview" src="" alt="Önizleme"
                                 class="img-fluid rounded mb-3 d-none"
                                 style="max-height:200px;width:100%;object-fit:cover;">
                        @endif
                        <input type="file" name="image" id="coverImage" class="form-control" accept="image/*">
                        <div class="text-muted small mt-1">Yeni dosya seçilirse mevcut görsel değiştirilir.</div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- Silme formu: DOM'da ana formun tamamen dışında --}}
    <form id="deleteForm"
          action="{{ route('admin.blogs.destroy', $blog) }}"
          method="POST"
          onsubmit="return confirm('Bu yazı silinecek. Emin misiniz?')">
        @csrf @method('DELETE')
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.4/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#blogContent',
    base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.8.4',
    suffix: '.min',
    language: 'tr',
    language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.11.6/langs6/tr.js',
    height: 500,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'wordcount'
    ],
    toolbar: [
        'undo redo | blocks | bold italic underline strikethrough',
        'forecolor backcolor | alignleft aligncenter alignright alignjustify',
        'bullist numlist outdent indent | link image table | code fullscreen'
    ].join(' | '),
    block_formats: 'Paragraf=p; Başlık 1=h1; Başlık 2=h2; Başlık 3=h3; Başlık 4=h4; Alıntı=blockquote; Kod=pre',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; font-size: 16px; line-height: 1.8; color: #333; padding: 12px; }',
    setup(editor) {
        editor.on('change', () => editor.save());
    }
});

document.getElementById('blogForm').addEventListener('submit', function () {
    tinymce.triggerSave();
});

document.getElementById('coverImage').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('coverPreview');
        img.src = e.target.result;
        img.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});
</script>
@endsection

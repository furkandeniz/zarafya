@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Yeni Blog Yazısı</h3>
            <h6 class="op-7 mb-2">Yeni bir blog yazısı oluştur</h6>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-round">
            <i class="fa fa-arrow-left me-1"></i> Geri Dön
        </a>
    </div>

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

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf

        <div class="row g-3">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">İçerik</h4></div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Başlık <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Blog yazısının başlığı">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Özet</label>
                            <textarea name="excerpt" class="form-control" rows="2" placeholder="Kısa özet (liste görünümünde gösterilir)">{{ old('excerpt') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">İçerik <span class="text-danger">*</span></label>
                            <textarea name="content" id="blogContent" class="form-control">{{ old('content') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header"><h4 class="card-title mb-0">Yayın Ayarları</h4></div>
                    <div class="card-body">

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1"
                                {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isPublished">Hemen Yayınla</label>
                            <div class="text-muted small">Kapalıysa taslak olarak kaydedilir.</div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-round">
                                <i class="fas fa-save me-1"></i> Kaydet
                            </button>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Kapak Görseli</h4></div>
                    <div class="card-body">
                        <div id="coverPreviewWrap" class="mb-3 d-none">
                            <img id="coverPreview" src="" alt="Önizleme" class="img-fluid rounded" style="max-height:200px;width:100%;object-fit:cover;">
                        </div>
                        <input type="file" name="image" id="coverImage" class="form-control" accept="image/*">
                        <div class="text-muted small mt-1">Maks. 4 MB. JPG, PNG, WEBP önerilir.</div>
                    </div>
                </div>
            </div>

        </div>
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
        document.getElementById('coverPreview').src = e.target.result;
        document.getElementById('coverPreviewWrap').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});
</script>
@endsection

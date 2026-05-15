@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="pt-2 pb-4">
        <h3 class="fw-bold mb-1">Blog Yorumları</h3>
        <p class="text-muted small mb-0">Bekleyen yorumları onaylayın veya silin.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Onay Bekleyenler --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                <i class="fas fa-clock me-1 text-warning"></i> Onay Bekleyenler
            </h4>
            @if($pending->count())
                <span class="badge bg-warning text-dark">{{ $pending->count() }}</span>
            @else
                <span class="badge bg-secondary">0</span>
            @endif
        </div>
        <div class="card-body p-0">
            @if($pending->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Yorum</th>
                                <th class="d-none d-md-table-cell">Blog Yazısı</th>
                                <th class="d-none d-sm-table-cell">Tarih</th>
                                <th class="text-end pe-3">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending as $comment)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold">{{ $comment->name }}</div>
                                        <div class="text-muted small">{{ $comment->email }}</div>
                                        <div class="mt-1">{{ Str::limit($comment->body, 120) }}</div>
                                    </td>
                                    <td class="d-none d-md-table-cell small">
                                        <a href="{{ route('blog.show', $comment->blog->slug) }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($comment->blog->title, 50) }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size:.7rem;"></i>
                                        </a>
                                    </td>
                                    <td class="d-none d-sm-table-cell small text-muted">
                                        {{ $comment->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="pe-3 text-end" style="white-space:nowrap;">
                                        <form action="{{ route('admin.blog-comments.approve', $comment) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-success btn-round" title="Onayla">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.blog-comments.destroy', $comment) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Bu yorum silinecek. Emin misiniz?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-round" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle me-1 text-success"></i> Bekleyen yorum yok.
                </div>
            @endif
        </div>
    </div>

    {{-- Onaylananlar --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                <i class="fas fa-check-circle me-1 text-success"></i> Onaylanan Yorumlar
            </h4>
            <span class="badge bg-secondary">{{ $approved->total() }}</span>
        </div>
        <div class="card-body p-0">
            @if($approved->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Yorum</th>
                                <th class="d-none d-md-table-cell">Blog Yazısı</th>
                                <th class="d-none d-sm-table-cell">Onay Tarihi</th>
                                <th class="text-center">Durum</th>
                                <th class="text-end pe-3">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approved as $comment)
                                <tr class="{{ $comment->is_visible ? '' : 'table-secondary' }}">
                                    <td class="ps-3">
                                        <div class="fw-semibold">{{ $comment->name }}</div>
                                        <div class="text-muted small">{{ $comment->email }}</div>
                                        <div class="mt-1 {{ $comment->is_visible ? '' : 'text-muted fst-italic' }}">
                                            {{ Str::limit($comment->body, 120) }}
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell small">
                                        <a href="{{ route('blog.show', $comment->blog->slug) }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($comment->blog->title, 50) }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size:.7rem;"></i>
                                        </a>
                                    </td>
                                    <td class="d-none d-sm-table-cell small text-muted">
                                        {{ $comment->approved_at?->format('d.m.Y H:i') ?? '—' }}
                                    </td>
                                    <td class="text-center">
                                        @if($comment->is_visible)
                                            <span class="badge bg-success">Görünür</span>
                                        @else
                                            <span class="badge bg-secondary">Gizli</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end" style="white-space:nowrap;">
                                        <form action="{{ route('admin.blog-comments.toggle-visibility', $comment) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm {{ $comment->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }} btn-round"
                                                    title="{{ $comment->is_visible ? 'Gizle' : 'Göster' }}">
                                                <i class="fas {{ $comment->is_visible ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.blog-comments.destroy', $comment) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Bu yorum silinecek. Emin misiniz?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-round" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($approved->hasPages())
                    <div class="d-flex justify-content-center py-3">
                        {{ $approved->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-4">Henüz onaylanmış yorum yok.</div>
            @endif
        </div>
    </div>

</div>
@endsection

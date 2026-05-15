@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Blog Yazıları</h3>
            <h6 class="op-7 mb-2">Tüm blog yazılarını yönet</h6>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-round">
            <i class="fas fa-plus me-1"></i> Yeni Yazı
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Yazılar</h4>
            <span class="badge bg-secondary">{{ $blogs->total() }} yazı</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40px;">#</th>
                            <th>Başlık</th>
                            <th class="d-none d-md-table-cell">Yazar</th>
                            <th>Durum</th>
                            <th class="d-none d-sm-table-cell">Tarih</th>
                            <th class="d-none d-md-table-cell text-center">Ziyaret</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $blog->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $blog->title }}</div>
                                    @if($blog->excerpt)
                                        <div class="text-muted small">{{ Str::limit($blog->excerpt, 60) }}</div>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell small text-muted">{{ $blog->author->name ?? '—' }}</td>
                                <td>
                                    @if($blog->is_published)
                                        <span class="badge bg-success">Yayında</span>
                                    @else
                                        <span class="badge bg-secondary">Taslak</span>
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell small text-muted">
                                    {{ $blog->published_at ? $blog->published_at->format('d.m.Y') : $blog->created_at->format('d.m.Y') }}
                                </td>
                                <td class="d-none d-md-table-cell text-center small text-muted">
                                    <i class="fas fa-eye me-1"></i>{{ number_format($blog->visit_count, 0, ',', '.') }}
                                </td>
                                <td class="pe-3" style="white-space:nowrap;">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <form action="{{ route('admin.blogs.toggle-publish', $blog) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm {{ $blog->is_published ? 'btn-outline-warning' : 'btn-outline-success' }} btn-round"
                                                    title="{{ $blog->is_published ? 'Yayından Kaldır' : 'Yayınla' }}">
                                                <i class="fas {{ $blog->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.blogs.edit', $blog) }}"
                                           class="btn btn-sm btn-outline-primary btn-round">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST"
                                              onsubmit="return confirm('Bu yazı silinecek. Emin misiniz?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-round">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Henüz blog yazısı yok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($blogs->hasPages())
                <div class="d-flex justify-content-center py-3">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

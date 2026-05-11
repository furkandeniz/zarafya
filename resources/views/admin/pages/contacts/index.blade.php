@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Mesajlar</h3>
            <h6 class="op-7 mb-2">İletişim formu mesajları</h6>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Tüm Mesajlar</h4>
            <span class="badge bg-secondary">{{ $messages->total() }} mesaj</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40px;">#</th>
                            <th>Gönderen</th>
                            <th>E-posta</th>
                            <th>Mesaj</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $msg)
                            <tr class="{{ $msg->read_at ? '' : 'fw-semibold' }}">
                                <td class="ps-3 text-muted small">{{ $msg->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if (!$msg->read_at)
                                            <span class="badge bg-danger" style="width:8px;height:8px;border-radius:50%;padding:0;flex-shrink:0;"></span>
                                        @else
                                            <span style="width:8px;flex-shrink:0;"></span>
                                        @endif
                                        {{ $msg->name }}
                                    </div>
                                </td>
                                <td class="small text-muted">{{ $msg->email }}</td>
                                <td class="small text-muted" style="max-width:260px;">
                                    {{ Str::limit($msg->message, 60) }}
                                </td>
                                <td>
                                    @if ($msg->status === 'open')
                                        <span class="badge bg-success">Açık</span>
                                    @else
                                        <span class="badge bg-secondary">Kapalı</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.contacts.show', $msg) }}"
                                       class="btn btn-sm btn-outline-primary btn-round">
                                        <i class="fas fa-eye"></i> Detay
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Bu mesaj silinecek. Emin misiniz?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger btn-round">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Henüz mesaj yok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($messages->hasPages())
                <div class="d-flex justify-content-center py-3">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

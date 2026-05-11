@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Mesajlar</h3>
            <h6 class="op-7 mb-2">İletişim formu mesajları</h6>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="d-flex align-items-center gap-3 flex-wrap">
                <label class="fw-semibold mb-0 text-nowrap">Konu Türüne Göre Filtrele:</label>
                <select name="type" class="form-select form-select-sm" style="width:200px;">
                    <option value="">Tüm Türler</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filtrele</button>
                @if(request('type'))
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary">Temizle</a>
                @endif
            </form>
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
                            <th class="d-none d-md-table-cell">E-posta</th>
                            <th>Tür</th>
                            <th class="d-none d-lg-table-cell">Mesaj</th>
                            <th class="d-none d-sm-table-cell">Durum</th>
                            <th class="d-none d-sm-table-cell">Tarih</th>
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
                                <td class="small text-muted d-none d-md-table-cell">{{ $msg->email }}</td>
                                <td>
                                    @if($msg->type)
                                        @php
                                            $typeColors = [
                                                'Soru'         => 'bg-info',
                                                'Öneri'        => 'bg-success',
                                                'Şikayet'      => 'bg-danger',
                                                'Bilgi Talebi' => 'bg-primary',
                                                'Diğer'        => 'bg-secondary',
                                            ];
                                            $badgeClass = $typeColors[$msg->type] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $msg->type }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="small text-muted d-none d-lg-table-cell" style="max-width:260px;">
                                    {{ Str::limit($msg->message, 60) }}
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    @if ($msg->status === 'open')
                                        <span class="badge bg-success">Açık</span>
                                    @else
                                        <span class="badge bg-secondary">Kapalı</span>
                                    @endif
                                </td>
                                <td class="small text-muted d-none d-sm-table-cell">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                                <td class="pe-3" style="white-space:nowrap;">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <a href="{{ route('admin.contacts.show', $msg) }}"
                                           class="btn btn-sm btn-outline-primary btn-round">
                                            <i class="fas fa-eye"></i> Detay
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST"
                                              onsubmit="return confirm('Bu mesaj silinecek. Emin misiniz?')">
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
                                <td colspan="8" class="text-center text-muted py-4">Henüz mesaj yok.</td>
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

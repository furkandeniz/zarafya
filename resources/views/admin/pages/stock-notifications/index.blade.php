@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Stok Bildirimleri</h3>
            <h6 class="op-7 mb-2">Stok gelince haber ver talepleri</h6>
        </div>
        @if ($pendingCount > 0)
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                {{ $pendingCount }} bekleyen bildirim
            </span>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtreler --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.stock-notifications.index') }}"
                  class="d-flex flex-wrap gap-2 align-items-end">

                <div style="min-width:200px;">
                    <label class="form-label small mb-1">E-posta</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="E-posta ara..." value="{{ request('search') }}">
                </div>

                <div style="min-width:180px;">
                    <label class="form-label small mb-1">Ürün</label>
                    <select name="product_id" class="form-control form-control-sm">
                        <option value="">— Tüm ürünler —</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="min-width:150px;">
                    <label class="form-label small mb-1">Durum</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">— Tümü —</option>
                        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Bekliyor</option>
                        <option value="notified" {{ request('status') === 'notified' ? 'selected' : '' }}>Gönderildi</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-round">
                        <i class="fa fa-search me-1"></i> Filtrele
                    </button>
                    <a href="{{ route('admin.stock-notifications.index') }}"
                       class="btn btn-sm btn-secondary btn-round">Temizle</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Bildirim Listesi</h4>
            <span class="badge bg-secondary">{{ $notifications->total() }} kayıt</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40px;">#</th>
                            <th>Ürün</th>
                            <th>Varyant</th>
                            <th>E-posta</th>
                            <th>Durum</th>
                            <th>Talep Tarihi</th>
                            <th>Gönderim Tarihi</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notifications as $n)
                            <tr class="{{ $n->notified_at ? 'text-muted' : '' }}">
                                <td class="ps-3 text-muted small">{{ $n->id }}</td>

                                <td>
                                    @if ($n->product)
                                        <a href="{{ route('admin.products.edit', $n->product->id) }}"
                                           class="text-decoration-none fw-semibold text-dark">
                                            {{ $n->product->name }}
                                        </a>
                                    @else
                                        <span class="text-muted small">Silinmiş ürün</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($n->variant_label)
                                        <span class="badge bg-light text-dark border">{{ $n->variant_label }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                <td class="small">{{ $n->email }}</td>

                                <td>
                                    @if ($n->notified_at)
                                        <span class="badge bg-success">Gönderildi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Bekliyor</span>
                                    @endif
                                </td>

                                <td class="small text-muted">{{ $n->created_at->format('d.m.Y H:i') }}</td>

                                <td class="small text-muted">
                                    {{ $n->notified_at ? $n->notified_at->format('d.m.Y H:i') : '—' }}
                                </td>

                                <td class="text-end pe-3">
                                    <form action="{{ route('admin.stock-notifications.destroy', $n) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Bu kayıt silinecek. Emin misiniz?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger btn-round">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-bell-slash me-2"></i>Bildirim kaydı bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($notifications->hasPages())
                <div class="d-flex justify-content-center py-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

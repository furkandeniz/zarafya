@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">İade Talepleri</h3>
            <h6 class="op-7 mb-2">Müşterilerin açtığı iade talepleri</h6>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtre --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.return-requests.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tüm Durumlar</option>
                        @foreach (\App\Models\ReturnRequest::STATUSES as $key => $s)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $s['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtrele</button>
                    @if (request('status'))
                        <a href="{{ route('admin.return-requests.index') }}" class="btn btn-outline-secondary btn-sm">Temizle</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Talep Listesi</h4>
            <span class="badge bg-secondary">{{ $returnRequests->total() }} talep</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Sipariş</th>
                            <th>Müşteri</th>
                            <th>Neden</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnRequests as $rr)
                            @php $s = \App\Models\ReturnRequest::STATUSES[$rr->status] ?? ['label' => $rr->status, 'class' => 'bg-secondary']; @endphp
                            <tr>
                                <td class="ps-3 text-muted small">{{ $rr->id }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $rr->order_id) }}"
                                       class="fw-semibold small text-decoration-none">
                                        #{{ $rr->order_id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $rr->user->name ?? '—' }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $rr->user->email ?? '' }}</div>
                                </td>
                                <td class="small">
                                    {{ \App\Models\ReturnRequest::REASONS[$rr->reason] ?? $rr->reason }}
                                </td>
                                <td>
                                    <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                                </td>
                                <td class="small text-muted">{{ $rr->created_at->format('d.m.Y H:i') }}</td>
                                <td class="pe-3">
                                    <a href="{{ route('admin.return-requests.show', $rr) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        İncele
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Henüz iade talebi yok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($returnRequests->hasPages())
        <div class="card-footer">
            {{ $returnRequests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

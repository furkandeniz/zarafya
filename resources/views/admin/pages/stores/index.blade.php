@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Mağazalar</h3>
                <h6 class="op-7 mb-2">Tüm marka mağazaları</h6>
            </div>
            <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-round">
                <i class="fa fa-plus me-1"></i> Mağaza Ekle
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Mağaza Listesi</h4>
                            <span class="badge bg-primary ms-2">{{ $stores->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Logo</th>
                                        <th>Mağaza Adı</th>
                                        <th>E-posta</th>
                                        <th>Telefon</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($stores as $store)
                                        <tr>
                                            <td>{{ $store->id }}</td>
                                            <td>
                                                @if ($store->logo)
                                                    <img src="{{ asset('storage/' . $store->logo) }}"
                                                         alt="{{ $store->name }}"
                                                         style="width:46px;height:46px;object-fit:cover;border-radius:50%;border:2px solid #eee;">
                                                @else
                                                    <div style="width:46px;height:46px;border-radius:50%;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-store text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $store->name }}</div>
                                                @if ($store->address)
                                                    <div class="text-muted small">{{ Str::limit($store->address, 40) }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $store->email ?? '—' }}</td>
                                            <td>{{ $store->phone ?? '—' }}</td>
                                            <td>
                                                @if ($store->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-store-id="{{ $store->id }}"
                                                        data-store-name="{{ $store->name }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Henüz mağaza bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $stores->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Silme Onay Modalı --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="fa fa-exclamation-triangle text-danger me-2"></i> Mağazayı Sil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="mb-0">
                        <strong id="deleteStoreName"></strong> mağazasını silmek istediğinizden emin misiniz?
                        Bu işlem geri alınamaz.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-round" data-bs-dismiss="modal">İptal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-round">
                            <i class="fa fa-trash me-1"></i> Evet, Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('deleteStoreName').textContent = btn.getAttribute('data-store-name');
            document.getElementById('deleteForm').action = '/admin/stores/' + btn.getAttribute('data-store-id');
        });
    </script>
    @endpush

@endsection

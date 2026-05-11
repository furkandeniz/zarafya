@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Kullanıcılar</h3>
                <h6 class="op-7 mb-2">Tüm kayıtlı kullanıcılar</h6>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-round">
                <i class="fa fa-plus me-1"></i> Kullanıcı Ekle
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info-circle me-1"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Ad, soyad veya e-posta ara..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select form-select-sm">
                            <option value="">Tüm Roller</option>
                            <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                            <option value="seller"   {{ request('role') === 'seller'   ? 'selected' : '' }}>Satıcı</option>
                            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Müşteri</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="store_id" class="form-select form-select-sm">
                            <option value="">Tüm Mağazalar</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrele</button>
                        @if (request('search') || request('role') || request('store_id'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Temizle</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Kullanıcı Listesi</h4>
                            <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ad Soyad</th>
                                        <th>E-posta</th>
                                        <th>Rol</th>
                                        <th>Mağaza</th>
                                        <th>E-posta Doğrulama</th>
                                        <th>Kayıt Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        @php
                                            $roleMap = ['admin' => ['Admin', 'bg-danger'], 'seller' => ['Satıcı', 'bg-info text-dark'], 'customer' => ['Müşteri', 'bg-secondary']];
                                            [$roleLabel, $roleClass] = $roleMap[$user->role ?? 'customer'];
                                        @endphp
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="badge {{ $roleClass }}">{{ $roleLabel }}</span></td>
                                            <td>
                                                @if ($user->store)
                                                    @if ($user->store->logo)
                                                        <img src="{{ asset('storage/' . $user->store->logo) }}"
                                                             style="width:22px;height:22px;object-fit:cover;border-radius:50%;margin-right:4px;">
                                                    @endif
                                                    {{ $user->store->name }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success">Doğrulandı</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Doğrulanmadı</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @unless ($user->email_verified_at)
                                                    <form action="{{ route('admin.users.resend-verification', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="Doğrulama Maili Gönder">
                                                            <i class="fa fa-envelope"></i>
                                                        </button>
                                                    </form>
                                                @endunless
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Henüz kullanıcı bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Silme Onay Modalı --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle text-danger me-2"></i> Kullanıcıyı Sil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="mb-0">
                        <strong id="deleteUserName"></strong> adlı kullanıcıyı silmek istediğinizden emin misiniz?
                        Bu işlem geri alınamaz.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-round" data-bs-dismiss="modal">
                        İptal
                    </button>
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
            const btn    = e.relatedTarget;
            const userId = btn.getAttribute('data-user-id');
            const userName = btn.getAttribute('data-user-name');

            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = '/admin/users/' + userId;
        });
    </script>
    @endpush

@endsection

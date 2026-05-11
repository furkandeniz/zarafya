@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Kategoriler</h3>
                <h6 class="op-7 mb-2">Tüm kategoriler</h6>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-round">
                <i class="fa fa-plus me-1"></i> Kategori Ekle
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
                            <h4 class="card-title">Kategori Listesi</h4>
                            <span class="badge bg-primary ms-2">{{ $categories->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kategori Adı</th>
                                        <th>Slug</th>
                                        <th>Açıklama</th>
                                        <th>Oluşturulma</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td><code>{{ $category->slug }}</code></td>
                                            <td>{{ $category->description ?? '—' }}</td>
                                            <td>{{ $category->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-category-id="{{ $category->id }}"
                                                        data-category-name="{{ $category->name }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Henüz kategori bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $categories->links() }}
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
                        <i class="fa fa-exclamation-triangle text-danger me-2"></i> Kategoriyi Sil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="mb-0">
                        <strong id="deleteCategoryName"></strong> kategorisini silmek istediğinizden emin misiniz?
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
            const btn          = e.relatedTarget;
            const categoryId   = btn.getAttribute('data-category-id');
            const categoryName = btn.getAttribute('data-category-name');

            document.getElementById('deleteCategoryName').textContent = categoryName;
            document.getElementById('deleteForm').action = '/admin/categories/' + categoryId;
        });
    </script>
    @endpush

@endsection

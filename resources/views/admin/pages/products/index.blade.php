@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Ürünler</h3>
                <h6 class="op-7 mb-2">Tüm ürünler</h6>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-round">
                <i class="fa fa-plus me-1"></i> Ürün Ekle
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Ürün adı ara..."
                               value="{{ request('search') }}">
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
                    <div class="col-md-3">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">Tüm Kategoriler</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrele</button>
                        @if (request('search') || request('store_id') || request('category_id'))
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Temizle</a>
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
                            <h4 class="card-title">Ürün Listesi</h4>
                            <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fotoğraf</th>
                                        <th>Ürün Adı</th>
                                        <th>Mağaza</th>
                                        <th>Kategori</th>
                                        <th>Fiyat</th>
                                        <th>Stok</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                <img src="{{ $product->firstImage ? asset('storage/' . $product->firstImage->image) : asset('images/product-1.png') }}"
                                                     alt="{{ $product->name }}"
                                                     style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->store->name ?? '—' }}</td>
                                            <td>{{ $product->category->name ?? '—' }}</td>
                                            <td>{{ number_format($product->price, 2, ',', '.') }} ₺</td>
                                            <td>
                                                @php $totalStock = $product->total_stock; @endphp
                                                @if ($product->stock === null)
                                                    <span class="badge bg-info" title="{{ $totalStock }} toplam stok">
                                                        {{ $totalStock }} <small>(varyant)</small>
                                                    </span>
                                                @elseif ($totalStock > 0)
                                                    <span class="badge bg-success">{{ $totalStock }}</span>
                                                @else
                                                    <span class="badge bg-danger">Tükendi</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $product->name }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Henüz ürün bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $products->links() }}
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
                        <i class="fa fa-exclamation-triangle text-danger me-2"></i> Ürünü Sil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="mb-0">
                        <strong id="deleteProductName"></strong> ürününü silmek istediğinizden emin misiniz?
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
            const btn         = e.relatedTarget;
            const productId   = btn.getAttribute('data-product-id');
            const productName = btn.getAttribute('data-product-name');

            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('deleteForm').action = '/admin/products/' + productId;
        });
    </script>
    @endpush

@endsection

@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-1">Arama Sonuçları</h3>
            <h6 class="op-7 mb-0">
                "<strong>{{ $q }}</strong>" için
                {{ $products->count() + $orders->count() + $users->count() + $messages->count() }} sonuç bulundu
            </h6>
        </div>
        <form action="{{ route('admin.search') }}" method="GET" class="d-flex gap-2 mt-3 mt-md-0">
            <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" style="min-width:220px;" autocomplete="off">
            <button type="submit" class="btn btn-primary btn-sm">Ara</button>
        </form>
    </div>

    {{-- ÜRÜNLER --}}
    @if ($products->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                <i class="fas fa-box me-2 text-muted"></i> Ürünler
            </h4>
            <span class="badge bg-primary">{{ $products->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Ürün Adı</th>
                            <th>Mağaza</th>
                            <th>Kategori</th>
                            <th>Fiyat</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $product->id }}</td>
                            <td class="fw-semibold small">{{ $product->name }}</td>
                            <td class="small">{{ $product->store->name ?? '—' }}</td>
                            <td class="small">{{ $product->category->name ?? '—' }}</td>
                            <td class="small">{{ $product->price ? number_format($product->price, 2, ',', '.') . ' ₺' : '—' }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- SİPARİŞLER --}}
    @if ($orders->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                <i class="fas fa-shopping-cart me-2 text-muted"></i> Siparişler
            </h4>
            <span class="badge bg-primary">{{ $orders->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Kullanıcı</th>
                            <th>Ürün</th>
                            <th>Tutar</th>
                            <th>Durum</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        @php $status = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                        <tr>
                            <td class="ps-3 text-muted small">#{{ $order->id }}</td>
                            <td>
                                <div class="fw-semibold small">{{ $order->user->name ?? '—' }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $order->user->email ?? '' }}</div>
                            </td>
                            <td class="small">{{ $order->firstItem->product_name ?? '—' }}</td>
                            <td class="small fw-semibold">{{ number_format($order->total_price, 2, ',', '.') }} ₺</td>
                            <td><span class="badge {{ $status['class'] }}" style="font-size:10px;">{{ $status['label'] }}</span></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Detay
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- KULLANICILAR --}}
    @if ($users->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                <i class="fas fa-users me-2 text-muted"></i> Kullanıcılar
            </h4>
            <span class="badge bg-primary">{{ $users->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Ad Soyad</th>
                            <th>E-posta</th>
                            <th>Rol</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @php
                            $roleMap = ['admin' => ['Admin', 'bg-danger'], 'seller' => ['Satıcı', 'bg-info text-dark'], 'customer' => ['Müşteri', 'bg-secondary']];
                            [$roleLabel, $roleClass] = $roleMap[$user->role ?? 'customer'];
                        @endphp
                        <tr>
                            <td class="ps-3 text-muted small">{{ $user->id }}</td>
                            <td class="fw-semibold small">{{ $user->name }}</td>
                            <td class="small text-muted">{{ $user->email }}</td>
                            <td><span class="badge {{ $roleClass }}">{{ $roleLabel }}</span></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- MESAJLAR --}}
    @if ($messages->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                <i class="fas fa-envelope me-2 text-muted"></i> İletişim Mesajları
            </h4>
            <span class="badge bg-primary">{{ $messages->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Gönderen</th>
                            <th>E-posta</th>
                            <th>Mesaj</th>
                            <th>Tarih</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $msg)
                        <tr>
                            <td class="ps-3 fw-semibold small">{{ $msg->name }}</td>
                            <td class="small text-muted">{{ $msg->email }}</td>
                            <td class="small text-muted" style="max-width:260px;">{{ Str::limit($msg->message, 60) }}</td>
                            <td class="small text-muted">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.contacts.show', $msg->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Detay
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- SONUÇ YOK --}}
    @if ($products->isEmpty() && $orders->isEmpty() && $users->isEmpty() && $messages->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-search fa-2x mb-3 d-block opacity-50"></i>
            "<strong>{{ $q }}</strong>" için herhangi bir sonuç bulunamadı.
        </div>
    </div>
    @endif

</div>
@endsection

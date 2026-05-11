@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Mesaj Detayı</h3>
            <h6 class="op-7 mb-2">
                <a href="{{ route('admin.contacts.index') }}" class="text-muted">Mesajlar</a>
                &rsaquo; #{{ $contact->id }}
            </h6>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-round">
            <i class="fas fa-arrow-left me-1"></i> Geri
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Mesaj İçeriği --}}
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Mesaj</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:48px;height:48px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-user text-muted"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $contact->name }}</div>
                            <div class="text-muted small">{{ $contact->email }}</div>
                        </div>
                        <div class="ms-auto text-muted small">
                            {{ $contact->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0" style="white-space:pre-wrap;line-height:1.8;">{{ $contact->message }}</p>
                </div>
            </div>
        </div>

        {{-- Durum Paneli --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Durum Yönetimi</h4>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <p class="text-muted small mb-1">Mevcut Durum</p>
                        @if ($contact->status === 'open')
                            <span class="badge bg-success fs-6 px-3 py-2">Açık</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">Kapalı</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <p class="text-muted small mb-1">Okunma</p>
                        @if ($contact->read_at)
                            <span class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>
                                {{ $contact->read_at->format('d.m.Y H:i') }}
                            </span>
                        @else
                            <span class="text-muted small">Okunmadı</span>
                        @endif
                    </div>

                    <hr>

                    <form action="{{ route('admin.contacts.update', $contact) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Durumu Güncelle</label>
                            <select name="status" class="form-select">
                                <option value="open"   {{ $contact->status === 'open'   ? 'selected' : '' }}>Açık</option>
                                <option value="closed" {{ $contact->status === 'closed' ? 'selected' : '' }}>Kapalı</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-round w-100">
                            <i class="fas fa-save me-1"></i> Güncelle
                        </button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                          onsubmit="return confirm('Bu mesaj silinecek. Emin misiniz?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-round w-100">
                            <i class="fas fa-trash me-1"></i> Mesajı Sil
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

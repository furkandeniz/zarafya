@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Yeni Kategori Ekle</h3>
                <h6 class="op-7 mb-2">Yeni bir kategori oluştur</h6>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-round">
                <i class="fa fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kategori Bilgileri</h4>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Kategori Adı <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Örn: Elektronik"
                                       required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">Slug otomatik oluşturulur.</div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="description">Açıklama</label>
                                <textarea id="description" name="description" rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Kategori hakkında kısa bir açıklama (isteğe bağlı)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-round w-100">
                                <i class="fa fa-plus me-1"></i> Kategori Oluştur
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Yeni Mağaza Ekle</h3>
                <h6 class="op-7 mb-2">Yeni bir marka mağazası oluştur</h6>
            </div>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary btn-round">
                <i class="fa fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mağaza Bilgileri</h4>
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

                        <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Logo Upload --}}
                            <div class="form-group mb-4 text-center">
                                <div class="mb-2">
                                    <div id="logoPreviewWrapper" style="display:inline-block;position:relative;">
                                        <img id="logoPreview" src="{{ asset('images/user.svg') }}"
                                             style="width:110px;height:110px;object-fit:cover;border-radius:50%;border:3px solid #eee;background:#f8f8f8;">
                                        <label for="logo"
                                               style="position:absolute;bottom:4px;right:4px;width:30px;height:30px;background:#007bff;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                               title="Logo Yükle">
                                            <i class="fa fa-camera text-white" style="font-size:13px;"></i>
                                            <input type="file" id="logo" name="logo" accept="image/*" class="d-none"
                                                   onchange="previewLogo(this)">
                                        </label>
                                    </div>
                                </div>
                                @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                <div class="form-text text-muted">JPG, PNG veya WEBP. Maks. 2MB.</div>
                            </div>

                            {{-- Ad --}}
                            <div class="form-group mb-3">
                                <label for="name">Mağaza Adı <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Marka / Mağaza adı"
                                       required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Açıklama --}}
                            <div class="form-group mb-3">
                                <label for="description">Açıklama</label>
                                <textarea id="description" name="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Mağaza hakkında kısa bir açıklama">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- E-posta & Telefon --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email">E-posta</label>
                                        <input type="email" id="email" name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}" placeholder="info@magaza.com">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone">Telefon</label>
                                        <input type="text" id="phone" name="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}" placeholder="+90 555 000 00 00">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Adres --}}
                            <div class="form-group mb-3">
                                <label for="address">Adres</label>
                                <input type="text" id="address" name="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}" placeholder="Şehir, İlçe, Sokak...">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Durum --}}
                            <div class="form-group mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active"
                                           name="is_active" value="1"
                                           {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Mağaza Aktif</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-round w-100">
                                <i class="fa fa-store me-1"></i> Mağazayı Oluştur
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('logoPreview').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush

@endsection

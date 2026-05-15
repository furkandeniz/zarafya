@if ($paginator->hasPages())
<nav class="zarafya-pagination" aria-label="Sayfalama">

    {{-- Mobil: sadece önceki / sonraki --}}
    <div class="zpag-mobile d-flex d-sm-none justify-content-between align-items-center w-100">
        @if ($paginator->onFirstPage())
            <span class="zpag-btn zpag-btn--disabled">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Önceki
            </span>
        @else
            <a class="zpag-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Önceki
            </a>
        @endif

        <span class="zpag-info">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a class="zpag-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">
                Sonraki
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <span class="zpag-btn zpag-btn--disabled">
                Sonraki
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif
    </div>

    {{-- Masaüstü: tam pagination --}}
    <div class="d-none d-sm-flex align-items-center justify-content-between w-100 gap-3">

        <span class="zpag-summary">
            <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
            arası gösteriliyor &mdash; toplam
            <strong>{{ $paginator->total() }}</strong> ürün
        </span>

        <ul class="zpag-list">

            {{-- Önceki --}}
            @if ($paginator->onFirstPage())
                <li class="zpag-item zpag-item--disabled">
                    <span class="zpag-page">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </span>
                </li>
            @else
                <li class="zpag-item">
                    <a class="zpag-page" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Önceki sayfa">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </a>
                </li>
            @endif

            {{-- Sayfa numaraları --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="zpag-item zpag-item--dots"><span class="zpag-page">···</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="zpag-item zpag-item--active" aria-current="page">
                                <span class="zpag-page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="zpag-item">
                                <a class="zpag-page" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Sonraki --}}
            @if ($paginator->hasMorePages())
                <li class="zpag-item">
                    <a class="zpag-page" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Sonraki sayfa">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </li>
            @else
                <li class="zpag-item zpag-item--disabled">
                    <span class="zpag-page">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </span>
                </li>
            @endif

        </ul>
    </div>

</nav>

<style>
.zarafya-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2rem;
    width: 100%;
}

/* Özet metin */
.zpag-summary {
    font-size: 13px;
    color: #888;
    white-space: nowrap;
}

/* Liste */
.zpag-list {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}

/* Her sayfa butonu */
.zpag-page {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #5A5A5A;
    background: #fff;
    border: 1.5px solid #e8e0d8;
    text-decoration: none;
    transition: background .18s, border-color .18s, color .18s, transform .15s;
    cursor: pointer;
    user-select: none;
}

a.zpag-page:hover {
    background: #EADFD6;
    border-color: #C49A6B;
    color: #C49A6B;
    transform: translateY(-1px);
}

/* Aktif sayfa */
.zpag-item--active .zpag-page {
    background: #C49A6B;
    border-color: #C49A6B;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(196, 154, 107, 0.35);
}

/* Devre dışı */
.zpag-item--disabled .zpag-page {
    background: #fafafa;
    border-color: #eee;
    color: #ccc;
    cursor: default;
}

/* Üç nokta */
.zpag-item--dots .zpag-page {
    border-color: transparent;
    background: transparent;
    color: #aaa;
    cursor: default;
    letter-spacing: 1px;
}

/* Mobil butonlar */
.zpag-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    color: #5A5A5A;
    background: #fff;
    border: 1.5px solid #e8e0d8;
    text-decoration: none;
    transition: background .18s, border-color .18s, color .18s;
}
a.zpag-btn:hover {
    background: #EADFD6;
    border-color: #C49A6B;
    color: #C49A6B;
}
.zpag-btn--disabled {
    color: #ccc;
    border-color: #eee;
    background: #fafafa;
    cursor: default;
}
.zpag-info {
    font-size: 13px;
    color: #888;
    font-weight: 500;
}
</style>
@endif

@props(['page' => 1, 'totalPages' => 1, 'total' => null, 'perPage' => 5])

@php
    $page = max(1, (int) $page);
    $totalPages = max(1, (int) $totalPages);
    $url = fn ($p) => request()->fullUrlWithQuery(['page' => $p]);
    // Fenêtre de pages : 1 … p-1 p p+1 … N
    $pages = collect([1, $page - 1, $page, $page + 1, $totalPages])
        ->filter(fn ($p) => $p >= 1 && $p <= $totalPages)->unique()->sort()->values();
@endphp

<div class="table-footer">
    <div class="d-flex align-items-center gap-2">
        <label for="perPage" class="mb-0">Lignes par page</label>
        <select id="perPage" class="form-select" data-list-param="perPage">
            @foreach ([5, 10, 20, 50] as $n)
                <option value="{{ $n }}" @selected((int) $perPage === $n)>{{ $n }}</option>
            @endforeach
        </select>
        @if (!is_null($total))
            <span class="d-none d-sm-inline">· {{ $total }} {{ $total > 1 ? 'résultats' : 'résultat' }}</span>
        @endif
    </div>

    @if ($totalPages > 1)
        <nav aria-label="Pagination">
            <ul class="pagination">
                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" data-list-link href="{{ $url(max(1, $page - 1)) }}" aria-label="Page précédente"><i class="bx bx-chevron-left"></i></a>
                </li>
                @foreach ($pages as $i => $p)
                    @if ($i > 0 && $p - $pages[$i - 1] > 1)
                        <li class="page-gap" aria-hidden="true">…</li>
                    @endif
                    <li class="page-item {{ $p === $page ? 'active' : '' }}">
                        <a class="page-link" data-list-link href="{{ $url($p) }}" @if ($p === $page) aria-current="page" @endif>{{ $p }}</a>
                    </li>
                @endforeach
                <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                    <a class="page-link" data-list-link href="{{ $url(min($totalPages, $page + 1)) }}" aria-label="Page suivante"><i class="bx bx-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    @endif
</div>

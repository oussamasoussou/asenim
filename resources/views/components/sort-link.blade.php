@props(['column', 'label', 'default' => null])

@php
    $sort = request('sort', $default);
    if ($sort === 'name') { $sort = 'first_name'; }
    $isActive = $sort === $column;
    $order = request('order', 'asc') === 'desc' ? 'desc' : 'asc';
    $next = $isActive && $order === 'asc' ? 'desc' : 'asc';
    $url = request()->fullUrlWithQuery(['sort' => $column, 'order' => $next, 'page' => null]);
    $icon = !$isActive ? 'bx-sort' : ($order === 'asc' ? 'bx-sort-up' : 'bx-sort-down');
@endphp

<a href="{{ $url }}" data-list-link class="th-sort {{ $isActive ? 'is-active' : '' }}">
    {{ $label }} <i class="bx {{ $icon }}" aria-hidden="true"></i>
</a>

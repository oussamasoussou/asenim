@props(['user' => null, 'size' => null, 'online' => false])

@php
    $first = $user->first_name ?? '';
    $last = $user->last_name ?? '';
    $initials = mb_strtoupper(mb_substr($first, 0, 1) . mb_substr($last, 0, 1)) ?: '?';
    $tone = $user ? ((int) ($user->id ?? 0)) % 6 : 0;
    $image = $user->image ?? null;
    $classes = 'ui-avatar tone-' . $tone . ($size ? ' is-' . $size : '') . ($online ? ' is-online' : '');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }} title="{{ trim($first . ' ' . $last) }}">
    @if ($image)
        <img src="{{ asset('storage/' . $image) }}" alt="" loading="lazy">
    @else
        <span aria-hidden="true">{{ $initials }}</span>
    @endif
</span>

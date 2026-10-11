@props(['icon' => 'bx-folder-open', 'title', 'text' => null, 'compact' => false])

<div {{ $attributes->merge(['class' => 'empty-state' . ($compact ? ' is-compact' : '')]) }}>
    <div class="empty-state-icon"><i class="bx {{ $icon }}"></i></div>
    <p class="empty-state-title">{{ $title }}</p>
    @if ($text)
        <p class="empty-state-text">{{ $text }}</p>
    @endif
    {{ $slot }}
</div>

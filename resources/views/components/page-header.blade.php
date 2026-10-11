@props(['title', 'subtitle' => null, 'back' => null])

<div {{ $attributes->merge(['class' => 'page-header']) }}>
    <div class="page-header-main">
        @if ($back)
            <a href="{{ $back }}" class="page-back" aria-label="Retour"><i class="bx bx-arrow-back"></i></a>
        @endif
        <div class="min-w-0">
            <h1 class="page-title">{{ $title }}</h1>
            @if ($subtitle)
                <p class="page-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if (trim($slot) !== '')
        <div class="page-actions">{{ $slot }}</div>
    @endif
</div>

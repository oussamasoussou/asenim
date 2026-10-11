{{-- Contenu d'une actualité / d'un événement pour la modale de lecture. --}}
<template data-article="{{ $item->id }}" data-title="{{ $item->title }}">
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        @if ($item->events_news === 'event')
            <span class="badge badge-success badge-dot">Événement</span>
        @else
            <span class="badge badge-primary badge-dot">Actualité</span>
        @endif
        <span class="text-muted small d-inline-flex align-items-center gap-1">
            <i class="bx bx-calendar"></i>
            {{ \Carbon\Carbon::parse($item->date)->translatedFormat($item->events_news === 'event' ? 'l j F Y · H\hi' : 'j F Y') }}
        </span>
        @if ($item->user)
            <span class="text-muted small d-inline-flex align-items-center gap-1">
                <i class="bx bx-user"></i>{{ $item->user->first_name }} {{ $item->user->last_name }}
            </span>
        @endif
    </div>
    @if ($item->image)
        <img src="{{ asset('storage/' . $item->image) }}" alt="" class="article-cover">
    @endif
    <div class="article-body rich-content">{!! \App\Support\RichText::render($item->content) !!}</div>
</template>

{{-- Carte d'actualité du site public. Variable : $item (News) --}}
@php
    $date = \Carbon\Carbon::parse($item->date);
    $isEvent = $item->events_news === 'event';
    $image = $item->image ? asset('storage/' . $item->image) : asset('front/assets/img/about.jpg');
@endphp
<article class="actualite-card" data-category="{{ $isEvent ? 'événement' : 'actualité' }}">
    <div class="actualite-image">
        <img src="{{ $image }}" alt="" loading="lazy">
        <span class="actualite-category">{{ $isEvent ? 'ÉVÉNEMENT' : 'ACTUALITÉ' }}</span>
        <div class="actualite-date">
            <strong>{{ $date->format('j') }}</strong>
            <span>{{ mb_strtoupper($date->translatedFormat('M')) }}</span>
            <small>{{ $date->format('Y') }}</small>
        </div>
    </div>
    <div class="actualite-content">
        <h2>{{ $item->title }}</h2>
        <p>{{ \App\Support\RichText::excerpt($item->content, 150) }}</p>
        <a href="{{ route('front.news.show', $item) }}" class="actualite-link">Lire la suite <i class="bi bi-arrow-right"></i></a>
    </div>
</article>

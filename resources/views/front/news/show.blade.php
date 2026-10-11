@extends('front.layout')

@php
    $date = \Carbon\Carbon::parse($news->date);
    $isEvent = $news->events_news === 'event';
    $image = $news->image ? asset('storage/' . $news->image) : asset('front/assets/img/about.jpg');
    // Texte riche saisi dans l'administration (nettoyé ; anciens textes bruts convertis en paragraphes)
    $body = \App\Support\RichText::render($news->content);
    $url = route('front.news.show', $news);
    $shareText = rawurlencode($news->title);
    $shareUrl = rawurlencode($url);
@endphp

@section('title', $news->title)
@section('description', \App\Support\RichText::excerpt($news->content, 155))
@section('body_class', 'starter-page-page inner-page')

@section('content')
  <main class="main news-detail-page">

    <section class="news-detail-hero" aria-labelledby="news-detail-title">
      <img class="news-detail-hero__image" src="{{ $image }}" alt="">
      <div class="news-detail-hero__overlay" aria-hidden="true"></div>

      <div class="news-detail-shell news-detail-hero__content">
        <nav class="news-detail-breadcrumb" aria-label="Fil d'Ariane">
          <a href="{{ route('front.home') }}">Accueil</a>
          <i class="bi bi-chevron-right" aria-hidden="true"></i>
          <a href="{{ route('front.news') }}">Actualités</a>
        </nav>

        <div class="news-detail-kicker">
          <span class="news-detail-badge">{{ $isEvent ? 'ÉVÉNEMENT' : 'ACTUALITÉ' }}</span>
          <span class="news-detail-kicker__separator" aria-hidden="true"></span>
          <span class="news-detail-date">
            <i class="bi bi-calendar3" aria-hidden="true"></i>
            <time datetime="{{ $date->toIso8601String() }}">{{ $date->translatedFormat('j F Y') }}</time>
          </span>
        </div>

        <h1 id="news-detail-title">{{ $news->title }}</h1>
      </div>
    </section>

    <section class="news-detail-body">
      <div class="news-detail-shell">
        <div class="news-detail-layout">

          <article class="news-detail-article">
            @if ($news->image)
              <figure class="news-detail-featured">
                <img src="{{ $image }}" alt="{{ $news->title }}">
              </figure>
            @endif

            <div class="news-detail-content">{!! $body !!}</div>

            <a href="{{ route('front.news') }}" class="news-detail-back">
              <i class="bi bi-grid-fill" aria-hidden="true"></i>
              <span>Retour aux actualités</span>
            </a>
          </article>

          <aside class="news-detail-sidebar" aria-label="Informations complémentaires">
            <section class="news-detail-sidecard news-detail-info">
              <h2>{{ $isEvent ? 'Informations sur l’événement' : 'Informations sur l’actualité' }}</h2>
              <div class="news-detail-info__item">
                <span class="news-detail-info__icon"><i class="bi bi-calendar3" aria-hidden="true"></i></span>
                <div>
                  <strong>{{ $date->translatedFormat($isEvent ? 'l j F Y' : 'j F Y') }}</strong>
                  @if ($isEvent)<br><span>{{ $date->format('H\hi') }}</span>@endif
                </div>
              </div>
              @if ($news->user)
                <div class="news-detail-info__item">
                  <span class="news-detail-info__icon"><i class="bi bi-person-fill" aria-hidden="true"></i></span>
                  <div><strong>Publié par {{ \Illuminate\Support\Str::title(mb_strtolower($news->user->first_name)) }} {{ $news->user->last_name }}</strong></div>
                </div>
              @endif
            </section>

            <section class="news-detail-sidecard news-detail-share">
              <h2>Partager cette actualité</h2>
              <div class="news-detail-share__links">
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="share-link share-link--linkedin" aria-label="Partager sur LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="share-link share-link--facebook" aria-label="Partager sur Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="share-link share-link--x" aria-label="Partager sur X"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="share-link share-link--whatsapp" aria-label="Partager sur WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <button type="button" class="share-link share-link--copy" aria-label="Copier le lien" data-copy-url="{{ $url }}"><i class="bi bi-link-45deg"></i></button>
              </div>
              <p class="front-copy-status" role="status" aria-live="polite"></p>
            </section>

            @if ($related->isNotEmpty())
              <section class="news-detail-sidecard news-detail-related">
                <div class="news-detail-related__head">
                  <h2>Autres actualités</h2>
                  <a href="{{ route('front.news') }}">Voir toutes les actualités <i class="bi bi-arrow-right"></i></a>
                </div>
                @foreach ($related as $item)
                  @php $d = \Carbon\Carbon::parse($item->date); @endphp
                  <a href="{{ route('front.news.show', $item) }}" class="news-detail-related__item">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('front/assets/img/about.jpg') }}" alt="" loading="lazy">
                    <div class="news-detail-related__content">
                      <span class="news-detail-related__badge">{{ $item->events_news === 'event' ? 'ÉVÉNEMENT' : 'ACTUALITÉ' }}</span>
                      <h3>{{ $item->title }}</h3>
                      <time datetime="{{ $d->toDateString() }}"><i class="bi bi-calendar3"></i> {{ $d->translatedFormat('j F Y') }}</time>
                    </div>
                  </a>
                @endforeach
              </section>
            @endif
          </aside>
        </div>
      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
document.addEventListener('click', function (e) {
  const btn = e.target.closest('[data-copy-url]');
  if (!btn) return;
  const status = btn.closest('.news-detail-share').querySelector('.front-copy-status');
  navigator.clipboard.writeText(btn.dataset.copyUrl).then(
    () => { status.textContent = 'Lien copié dans le presse-papiers.'; },
    () => { status.textContent = 'Copie impossible : copiez l’adresse depuis la barre du navigateur.'; }
  );
});
</script>
@endpush

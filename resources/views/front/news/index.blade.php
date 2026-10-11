@extends('front.layout')

@section('title', 'Actualités')
@section('body_class', 'starter-page-page inner-page')

@push('styles')
<style>
    .tab a { color: black !important; }
  </style>
@endpush

@section('content')


<main class="main">

    <!-- =====================================================
         BANDEAU ACTUALITÉS
         ===================================================== -->
    <section class="actualities-banner">

      <img
        src="{{ asset('front/assets/img/back29.png') }}"
        alt=""
        class="actualities-banner__image"
      >

      <div class="container actualities-banner__content">

        <!-- PARTIE GAUCHE -->
        <div class="actualities-banner__text">

          <div class="actualities-banner__label">
            <span class="actualities-banner__label-icon"></span>
            <span>ASENIM</span>
            <span class="actualities-banner__label-line"></span>
          </div>

          <h1>Actualités</h1>

          <p>
            Toute l'actualité de l'association<br>
            et de ses membres diplômés
          </p>

        </div>

        <!-- PARTIE DROITE : VALEURS -->
        <div class="actualities-banner__values">

          <div class="actualities-banner__value actualities-banner__value--standard">
            <div class="actualities-banner__icon">
              <i class="bi bi-calendar2-week-fill"></i>
            </div>
            <span>Rendez-vous</span>
          </div>

          <div class="actualities-banner__value actualities-banner__value--standard">
            <div class="actualities-banner__icon">
              <i class="bi bi-megaphone-fill"></i>
            </div>
            <span>Evènements</span>
          </div>

          <div class="actualities-banner__value actualities-banner__value--search">
            <div class="actualities-banner__icon actualities-banner__search-trigger" aria-hidden="true">
              <i class="bi bi-search"></i>
            </div>
            <form class="actualities-banner__search-form" role="search" action="{{ route('front.news') }}" method="get">
              <input class="actualities-banner__search-input" type="search" name="q" value="{{ request('q') }}"
                     placeholder="Rechercher..." aria-label="Rechercher" autocomplete="off">
            </form>
          </div>

        </div>

      </div>

    </section>

    <!-- =====================================================
         SECTION ACTUALITÉS — alimentée par l'administration
    ===================================================== -->
    <section id="Act" class="actualites-section">
      <div class="actualites-container">

        @if (request('q'))
          <div class="front-search-summary">
            <p>
              {{ $news->total() }} {{ $news->total() > 1 ? 'résultats' : 'résultat' }} pour « <strong>{{ request('q') }}</strong> »
            </p>
            <a href="{{ route('front.news') }}">Effacer la recherche <i class="bi bi-x-lg"></i></a>
          </div>
        @endif

        @if ($news->isEmpty())
          <div class="front-empty">
            <i class="bi bi-newspaper" aria-hidden="true"></i>
            <h2>{{ request('q') ? 'Aucune actualité trouvée' : 'Aucune actualité pour le moment' }}</h2>
            <p>{{ request('q') ? 'Essayez avec un autre mot-clé.' : 'Revenez bientôt : les prochaines actualités de l’ASENIM seront publiées ici.' }}</p>
          </div>
        @else
          @if ($featured->isNotEmpty())
            <div class="actualites-featured-frame">
              <div class="actualites-frame-heading">
                <h2>À la une</h2>
                <span class="actualites-frame-heading__line" aria-hidden="true"></span>
              </div>
              <div class="actualites-grid actualites-grid--featured">
                @foreach ($featured as $item)
                  @include('front.news.partials.card', ['item' => $item])
                @endforeach
              </div>
            </div>
          @endif

          @if ($others->isNotEmpty())
            <div class="actualites-other-frame">
              <div class="actualites-grid actualites-grid--others" id="actualitesGrid">
                @foreach ($others as $item)
                  @include('front.news.partials.card', ['item' => $item])
                @endforeach
              </div>
            </div>
          @endif

          @if ($news->lastPage() > 1)
            @php
              $current = $news->currentPage();
              $last = $news->lastPage();
              $pages = collect([1, $current - 1, $current, $current + 1, $last])->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
            @endphp
            <nav class="actualites-pagination" aria-label="Pagination des actualités">
              @if ($news->onFirstPage())
                <span class="page-arrow is-disabled" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
              @else
                <a class="page-arrow" href="{{ $news->previousPageUrl() }}" aria-label="Page précédente"><i class="bi bi-chevron-left"></i></a>
              @endif

              @foreach ($pages as $i => $p)
                @if ($i > 0 && $p - $pages[$i - 1] > 1)
                  <span class="page-dots">…</span>
                @endif
                <a class="page-number {{ $p === $current ? 'active' : '' }}" href="{{ $news->url($p) }}" @if ($p === $current) aria-current="page" @endif>{{ $p }}</a>
              @endforeach

              @if ($news->hasMorePages())
                <a class="page-arrow" href="{{ $news->nextPageUrl() }}" aria-label="Page suivante"><i class="bi bi-chevron-right"></i></a>
              @else
                <span class="page-arrow is-disabled" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
              @endif
            </nav>
          @endif
        @endif
      </div>
    </section>
  </main>
@endsection

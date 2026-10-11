@extends('front.layout')

@section('title', 'Bureau exécutif')
@section('body_class', 'starter-page-page inner-page')

@push('styles')
<style>
    .tab a { color: black !important; }
  </style>
@endpush

@section('content')


<main class="main">

    <!-- =====================================================
         BANDEAU BUREAU EXÉCUTIF
         ===================================================== -->
    <section class="executive-banner">

      <img
        src="{{ asset('front/assets/img/back27.png') }}"
        alt=""
        class="executive-banner__image"
      >

      <div class=" container executive-banner__content">

        <!-- PARTIE GAUCHE -->
        <div class="executive-banner__text">

          <div class="executive-banner__label">
            <span class="executive-banner__label-icon"></span>
            <span>ASENIM</span>
            <span class="executive-banner__label-line"></span>
          </div>

          <h1>Bureau Exécutif</h1>

          <p>
            Une équipe engagée au service<br>
            des diplômés et de notre communauté
          </p>

        </div>

        <!-- PARTIE DROITE : VALEURS -->
        <div class="executive-banner__values">

          <!-- Engagement -->
          <div class="executive-banner__value executive-banner__value--standard">
            <div class="executive-banner__icon">
              <i class="bi bi-people"></i>
            </div>
            <span>Engagement</span>
          </div>

          <!-- Solidarité -->
          <div class="executive-banner__value executive-banner__value--standard">
            <div class="executive-banner__icon">
              <i class="fa-solid fa-handshake"></i>
            </div>
            <span>Solidarité</span>
          </div>

          <!-- Recherche -->
          <div class="executive-banner__value executive-banner__value--search">
            <div class="executive-banner__icon executive-banner__search-trigger" aria-hidden="true">
              <i class="bi bi-search"></i>
            </div>

            <form class="executive-banner__search-form" role="search" action="{{ route('front.bureau') }}" method="get">
              <input
                class="executive-banner__search-input"
                type="search"
                name="q"
              value="{{ request('q') }}"
                placeholder="Rechercher..."
                aria-label="Rechercher"
                autocomplete="off"
              >
            </form>
          </div>

        </div>

      </div>

    </section>

    <!-- =====================================================
         GRILLE UNIQUE — BUREAU EXÉCUTIF
         Toutes les cartes dans une seule grille continue.
         Le JS révèle une rangée complète à chaque clic.
         ===================================================== -->
    <section class="executif-section">
      <div class="team-container">
        <div class="team-grid">
        @forelse ($members as $member)
          <article class="team-card {{ $loop->index % 3 === 1 ? 'member-blue' : 'member-green' }} asenim-card">
            <div class="avatar-wrapper">
              <img src="{{ $member->photoUrl() }}" alt="{{ $member->full_name }}" loading="lazy">
            </div>
            <div class="member-info">
              <h3 class="name">{{ $member->full_name }}</h3>
              <p class="role">
                <i class="bi bi-person"></i>
                <span>{{ $member->role }}</span>
              </p>
              @if ($member->organization)
                <p class="org">
                  <i class="bi bi-building"></i>
                  <span>{{ $member->organization }}</span>
                </p>
              @endif
              @if ($member->email)
                <p class="email">
                  <i class="bi bi-envelope"></i>
                  <a href="mailto:{{ $member->email }}">{{ $member->email }}</a>
                </p>
              @endif
            </div>
          </article>
        @empty
          <div class="front-empty" style="grid-column: 1 / -1;">
            <i class="bi bi-people" aria-hidden="true"></i>
            <h2>{{ request('q') ? 'Aucun membre trouvé' : 'Bureau exécutif bientôt présenté' }}</h2>
            <p>{{ request('q') ? 'Essayez avec un autre nom ou une autre fonction.' : 'La composition du bureau sera publiée prochainement.' }}</p>
          </div>
        @endforelse
      </div>
    </div>

<!-- Bouton Voir plus -->
      <button id="showMoreBtn" class="show-more-btn" type="button">
        Voir plus <i class="bi bi-chevron-down"></i>
      </button>
    </section>

  </main>

  <!-- =====================================================
       FOOTER
       ===================================================== -->
@endsection

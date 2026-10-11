@extends('front.layout')

@section('title', 'Galerie')
@section('body_class', 'starter-page-page gallery-page')

@push('styles')
<style>
    .tab a { color: black !important; }
  </style>
@endpush

@section('content')

    <!-- =====================================================
         GALERIE — maquette codée d'après le visuel de référence
         Les 3 images du bandeau sont volontairement des balises <img src="...">
         afin que vous puissiez les remplacer très facilement.
         ===================================================== -->
    <section id="asenim-gallery" class="asenim-gallery" aria-labelledby="gallery-title">

      <!-- HERO / BANDEAU SUPÉRIEUR -->
      <div class="gallery-hero">
        <div class="gallery-hero__inner">

          <div class="gallery-hero__copy">
            <div class="gallery-hero__eyebrow">
              <i class="bi bi-camera-fill"></i>
              <span>DÉCOUVRIR NOS TEMPS FORTS</span>
            </div>

            <h1 id="gallery-title">Galerie</h1>
            <p class="gallery-hero__motto">Plus qu’une association,<br>une grande famille !</p>
            <span class="gallery-hero__stroke" aria-hidden="true"></span>
          </div>

          <!-- Collage : 1 grande photo à gauche + 2 photos superposées à droite. -->
          <div class="gallery-hero__collage" aria-label="Souvenirs ASENIM">
            <!-- IMAGE 1 DU BANDEAU : remplacez uniquement la valeur de src -->
            <figure class="gallery-hero__photo gallery-hero__photo--main">
              <img src="{{ asset('front/assets/img/enim_image.jpg') }}" alt="Campus de l'ENIM">
            </figure>

            <!-- IMAGE 2 DU BANDEAU : remplacez uniquement la valeur de src -->
            <figure class="gallery-hero__photo gallery-hero__photo--top">
              <img src="{{ asset('front/assets/img/enim-image2.jpg') }}" alt="Membres de l'ASENIM">
            </figure>

            <!-- IMAGE 3 DU BANDEAU : remplacez uniquement la valeur de src -->
            <figure class="gallery-hero__photo gallery-hero__photo--bottom">
              <img src="{{ asset('front/assets/img/enim-image3.jpg') }}" alt="Événement ASENIM">
            </figure>

            
          </div>
        </div>
      </div>

     

      <!-- GRILLE GALERIE -->
      <div class="gallery-shell">
        <div class="gallery-grid">

          <article class="gallery-card gallery-card--featured">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/conference-innovation-sociale.jpg') }}" alt="Affiche Conférence Innovation Sociale">
              <span class="gallery-card__badge gallery-card__badge--green">Conférence</span>
            </div>
            <div class="gallery-card__body">
              <h2>Conférence Innovation Sociale</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>12 septembre 2026</span></div>
              <a class="gallery-card__arrow" href="{{ route('front.icmic') }}" aria-label="Voir Conférence Innovation Sociale"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/forum-emploi-2026.jpg') }}" alt="Affiche Forum Emploi 2026">
              <span class="gallery-card__badge gallery-card__badge--blue">Emploi</span>
            </div>
            <div class="gallery-card__body">
              <h2>Forum Emploi 2026</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>24 octobre 2026</span></div>
              <a class="gallery-card__arrow" href="{{ route('front.icmic') }}" aria-label="Voir Forum Emploi 2026"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/journee-scientifique.jpg') }}" alt="Affiche Journée Scientifique">
              <span class="gallery-card__badge gallery-card__badge--green">Science</span>
            </div>
            <div class="gallery-card__body">
              <h2>Journée Scientifique</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>14 novembre 2026</span></div>
              <a class="gallery-card__arrow" href="#" aria-label="Voir Journée Scientifique"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/journee-scientifique-02.jpg') }}" alt="Affiche Journée Scientifique">
              <span class="gallery-card__badge gallery-card__badge--green">Science</span>
            </div>
            <div class="gallery-card__body">
              <h2>Journée Scientifique</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>14 novembre 2026</span></div>
              <a class="gallery-card__arrow" href="#" aria-label="Voir Journée Scientifique"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/atelier-leadership.jpg') }}" alt="Affiche Atelier Leadership">
              <span class="gallery-card__badge gallery-card__badge--green">Formation</span>
            </div>
            <div class="gallery-card__body">
              <h2>Atelier Leadership</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>28 novembre 2026</span></div>
              <a class="gallery-card__arrow" href="#" aria-label="Voir Atelier Leadership"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/rencontre-alumni.jpg') }}" alt="Affiche Rencontre Alumni">
              <span class="gallery-card__badge gallery-card__badge--blue">Alumni</span>
            </div>
            <div class="gallery-card__body">
              <h2>Rencontre Alumni</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>16 janvier 2027</span></div>
              <a class="gallery-card__arrow" href="#" aria-label="Voir Rencontre Alumni"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/hackathon-solidaire.jpg') }}" alt="Affiche Hackathon Solidaire">
              <span class="gallery-card__badge gallery-card__badge--green">Projet</span>
            </div>
            <div class="gallery-card__body">
              <h2>Hackathon Solidaire</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>20 février 2027</span></div>
              <a class="gallery-card__arrow" href="#" aria-label="Voir Hackathon Solidaire"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

          <article class="gallery-card">
            <div class="gallery-card__media">
              <img src="{{ asset('front/assets/img/galerie/atelier-leadership-02.jpg') }}" alt="Affiche Atelier Leadership">
              <span class="gallery-card__badge gallery-card__badge--green">Formation</span>
            </div>
            <div class="gallery-card__body">
              <h2>Atelier Leadership</h2>
              <div class="gallery-card__meta"><i class="bi bi-calendar3"></i><span>28 novembre 2026</span></div>
              <a class="gallery-card__arrow" href="#" aria-label="Voir Atelier Leadership"><i class="bi bi-chevron-right"></i></a>
            </div>
          </article>

         


        </div>

        <!-- PAGINATION VISUELLE -->
        <nav class="gallery-pagination" aria-label="Pagination de la galerie">
          <a href="#" class="gallery-page-btn gallery-page-btn--arrow" aria-label="Page précédente"><i class="bi bi-chevron-left"></i></a>
          <a href="#" class="gallery-page-btn is-active" aria-current="page">1</a>
          <a href="#" class="gallery-page-btn">2</a>
          <a href="#" class="gallery-page-btn">3</a>
          <span class="gallery-page-btn gallery-page-btn--dots">…</span>
          <a href="#" class="gallery-page-btn">8</a>
          <a href="#" class="gallery-page-btn gallery-page-btn--arrow" aria-label="Page suivante"><i class="bi bi-chevron-right"></i></a>
        </nav>
      </div>

    </section>

  </main>

  <!-- =====================================================
       FOOTER
       ===================================================== -->
@endsection

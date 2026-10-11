@extends('front.layout')

@section('title', 'Qui sommes-nous')
@section('body_class', '')

@push('styles')
<style>
    .tab a { color: black !important; }
  </style>
@endpush

@section('content')


  <main class="main Us-section-page">
  <section id="Qui_sommes_nous" class="Qui_sommes_nous team-section">
  <div class="container" data-aos="fade-up">
    <div class="section-title" data-aos="fade-up">
      <span class="section-subtitle">DÉCOUVREZ L'ASSOCIATION</span>
      <h2 class="title">Qui sommes-nous</h2>
    </div>
  </div>
</section>
    <section class="Us-section-detail who-mosaic-section" aria-labelledby="who-mosaic-title">

      <div class="who-mosaic-heading">
        
        <!-- =========================================================
     CHIFFRES CLÉS — variante dédiée à la page Qui sommes-nous
     ========================================================= -->
<section id="asenim-who-stats" aria-label="Chiffres clés ASENIM">
  <div class="who-stats__wrapper">

    <div class="who-stats__item">
      <span class="who-stats__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
      <span class="who-stats__text">
        <strong class="counter"
                data-counter-target="150"
                data-counter-prefix="+ "
                data-counter-duration="2200">+ 0</strong>
        <small>Membres Asenim</small>
      </span>
    </div>

    <div class="who-stats__item">
      <span class="who-stats__icon"><i class="bi bi-star-fill" aria-hidden="true"></i></span>
      <span class="who-stats__text">
        <strong class="counter"
                data-counter-target="20"
                data-counter-prefix="+ "
                data-counter-duration="2000">+ 0</strong>
        <small>Projets marquants</small>
      </span>
    </div>

    <div class="who-stats__item">
      <span class="who-stats__icon"><i class="bi bi-calendar3" aria-hidden="true"></i></span>
      <span class="who-stats__text">
        <strong class="counter"
                data-counter-target="30"
                data-counter-prefix="+ "
                data-counter-duration="2000">+ 0</strong>
        <small>Événements organisés</small>
      </span>
    </div>

  </div>
</section>
		
      </div>

      <div class="who-mosaic-grid">

      <article class="who-tile who-tile--text who-tile--text-1">
  <div class="who-tile__content">
    <p class="who-tile__quote">
      « Créer des liens durables entre les générations d’Enimiens »
    </p>
    <p class="who-tile__source">
      <a href="#" class="who-tile__toggle"
         role="button"
         aria-expanded="false"
         aria-controls="expand-1">Lire la suite</a>
    </p>
  </div>

  <div class="who-tile__expand" id="expand-1" aria-hidden="true">
    <div>
      <p>
        L’ASENIM œuvre chaque jour pour tisser des ponts entre les promotions
        d’hier et celles d’aujourd’hui : mentorat, transmission de savoir-faire,
        entraide professionnelle et événements intergénérationnels.
      </p>
      <p>
        Parce que la force d’une école réside dans la solidité de son réseau,
        nous faisons vivre cet héritage à travers des actions concrètes et
        durables.
      </p>
	  <p>
        L’ASENIM œuvre chaque jour pour tisser des ponts entre les promotions
        d’hier et celles d’aujourd’hui : mentorat, transmission de savoir-faire,
        entraide professionnelle et événements intergénérationnels.
      </p>
    </div>
  </div>
</article>

        <!-- BLOC TEXTE 2 -->
     <article class="who-tile who-tile--text who-tile--text-2">
  <div class="who-tile__content">
    <p class="who-tile__quote">
      « Une communauté engagée au service de l’excellence »
    </p>
    <p class="who-tile__source">
      <a href="#" class="who-tile__toggle"
         role="button"
         aria-expanded="false"
         aria-controls="expand-2">Lire la suite</a>
    </p>
  </div>

  <div class="who-tile__expand" id="expand-2" aria-hidden="true">
    <div>
      <p>
        Nos membres partagent une même exigence : celle de l’excellence, dans
        les études, la recherche et la vie professionnelle.
      </p>
      <p>
        Cette exigence se traduit par un accompagnement continu des étudiants,
        la valorisation des parcours et la promotion du génie énimien bien
        au-delà des frontières.
      </p>
    </div>
  </div>
</article>

        <!-- IMAGE 1 -->
        <figure class="who-tile who-tile--image who-tile--image-1">
          <img src="{{ asset('front/assets/img/enim-image2.jpg') }}" alt="Communauté ASENIM">
        </figure>

        <!-- IMAGE 2 : grande image verticale -->
        <figure class="who-tile who-tile--image who-tile--image-2">
          <img src="{{ asset('front/assets/img/enim-longue.png') }}" alt="Membre de la communauté ASENIM">
        </figure>

        <!-- BLOC TEXTE FUSIONNÉ : occupe les colonnes 2 et 3 sur la ligne 2 -->
<article class="who-tile who-tile--text who-tile--text-wide">
  <div class="who-tile__content">
    <p class="who-tile__quote">
      « Partager, collaborer et faire grandir le réseau Enimien »
    </p>
    <p class="who-tile__source">
      <a href="#" class="who-tile__toggle"
         role="button"
         aria-expanded="false"
         aria-controls="expand-3">Lire la suite</a>
    </p>
  </div>

  <div class="who-tile__expand" id="expand-3" aria-hidden="true">
    <div>
      <p>
        Conférences, afterworks, ateliers carrière, rencontres annuelles :
        chaque occasion est bonne pour créer du lien et faire émerger de
        nouvelles synergies entre Enimiens.
      </p>
      <p>
        Nous croyons qu’un réseau vivant se construit dans la durée, avec
        régularité et générosité. Rejoindre l’ASENIM, c’est rejoindre une
        famille professionnelle engagée.
      </p>
    </div>
  </div>
</article>

        <!-- BLOC TEXTE 4 -->
       <article class="who-tile who-tile--text2 who-tile--text-4">
  <div class="who-tile__content">
    <p class="who-tile__quote">
      « Ensemble, nous construisons de nouvelles opportunités »
    </p>
    <p class="who-tile__source">
      <a href="#" class="who-tile__toggle"
         role="button"
         aria-expanded="false"
         aria-controls="expand-4">Lire la suite</a>
    </p>
  </div>

  <div class="who-tile__expand" id="expand-4" aria-hidden="true">
    <div>
      <p>
        Stages, premiers emplois, partenariats industriels, projets
        entrepreneuriaux : l’ASENIM facilite les connexions entre ses membres
        et les acteurs économiques.
      </p>
      <p>
        Notre ambition : que chaque Enimien trouve, au sein du réseau, les
        ressources et les soutiens nécessaires pour faire aboutir ses projets.
      </p>
    </div>
  </div>
</article>

        <!-- IMAGE 4 -->
        <figure class="who-tile who-tile--image who-tile--image-4">
          <img src="{{ asset('front/assets/img/enim-image3.jpg') }}" alt="Rencontre de la communauté ASENIM">
        </figure>

      </div>

     

    </section>

  </main>

  <!-- =====================================================
       FOOTER
       ===================================================== -->
@endsection

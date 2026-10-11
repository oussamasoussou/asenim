@extends('front.layout')

@section('title', 'Accueil')
@section('body_class', 'index-page')

@section('content')



  <main class="main">
    <!-- Hero Section - style inspiré de indexdeep.html -->
    <section class="hero position-relative px-0">
      <div id="heroCarousel" class="carousel slide carousel-fade usm-hero-carousel" data-hero-interval="6500" aria-roledescription="carousel">
        <div class="carousel-inner">

          <div class="carousel-item active">
            <img src="{{ asset('front/assets/img/back30.png') }}" class="d-block w-100 hero-slide" alt="Communauté ASENIM">
            <div class="carousel-caption">
              <p class="hero-eyebrow">ASSOCIATION SCIENTIFIQUE DE L’ENIM</p>
              <h1>Bienvenue à <span>ASENIM</span></h1>
              <p>Une communauté engagée pour soutenir l’ENIM et construire ensemble un avenir d’excellence.</p>
              <a href="#about" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold">
                QUI SOMMES-NOUS <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
              </a>
            </div>
          </div>

<div class="carousel-item">
            <img src="{{ asset('front/assets/img/enim-image2.jpg') }}" class="d-block w-100 hero-slide" alt="Communauté ASENIM">
            <div class="carousel-caption">
              <p class="hero-eyebrow">ASSOCIATION SCIENTIFIQUE DE L’ENIM</p>
              <h1>Bienvenue à <span>ASENIM</span></h1>
              <p>Une communauté engagée pour soutenir l’ENIM et construire ensemble un avenir d’excellence.</p>
              <a href="#about" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold">
                QUI SOMMES-NOUS <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
              </a>
            </div>
          </div>


          <div class="carousel-item">
            <img src="{{ asset('front/assets/img/about.jpg') }}" class="d-block w-100 hero-slide" alt="Communauté Enimienne">
            <div class="carousel-caption">
              <p class="hero-eyebrow">UNE COMMUNAUTÉ QUI NOUS RASSEMBLE</p>
              <h1>Plus qu’une école,<br><span>une grande famille !</span></h1>
              <p>Créer du lien entre diplômés, étudiants et enseignants, partager les expériences et faire grandir le réseau Enimien.</p>
              <a href="#community" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold">
                REJOINDRE LA COMMUNAUTÉ <i class="bi bi-people-fill ms-1" aria-hidden="true"></i>
              </a>
            </div>
          </div>
		  
		   
        </div>

        <button class="carousel-control-prev usm-hero-arrow usm-hero-arrow--prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Image précédente">
          <span class="usm-hero-arrow__icon" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
        </button>

        <button class="carousel-control-next usm-hero-arrow usm-hero-arrow--next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Image suivante">
          <span class="usm-hero-arrow__icon" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
        </button>

        <div class="carousel-indicators">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
		  <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
      </div>

      <div class="stats-box" data-aos="fade-left" data-aos-delay="300" data-aos-easing= "linear" data-aos-duration="400" >
  <h5 class="mb-3 fw-bold">DEPUIS <strong>1987</strong>, LE RÉSEAU ASENIM GRANDIT</h5>
  <div class="d-flex align-items-center justify-content-between flex-wrap">
    <div>
      <strong class="fs-3 counter"
              data-counter-target="5000"
              data-counter-prefix="+"
              data-counter-duration="2400">+0</strong>
      <div>Alumni connectés</div>
    </div>
    <div class="stats-separator"></div>
    <div>
      <strong class="fs-3 counter"
              data-counter-target="1987"
              data-counter-duration="2000"
              data-counter-raw="true">0</strong>
      <div>Année de création</div>
    </div>
  </div>
</div>
    </section> <!-- /Hero Section -->

<section id="about" class="about-globe section">
  <div class="about-bg-word" aria-hidden="true">ASENIM</div>
  <div class="about-decor about-decor--one" aria-hidden="true"></div>
  <div class="about-decor about-decor--two" aria-hidden="true"></div>

  <div class="container about-shell">
    <div class="row align-items-center g-4 g-xl-5">

      <!-- LEFT CONTENT -->
      <div class="col-lg-6">
        <div class="about-content" data-aos="fade-right">
          <span class="about-tag">
            <span class="dot"></span>
            EST. 1987
          </span>

          <p class="about-kicker">Qui sommes-nous ?</p>

       <h2>
  <span class="about-h2-line">Notre mission, connecter</span><br>
  <span class="about-h2-line">les</span>
  <span class="about-h2-accent">générations Enimiennes</span>
</h2>

          <p class="about-intro">
            L’ASENIM renforce les liens entre anciens étudiants, étudiants et enseignants,
            soutient l’ENIM dans ses projets et contribue ensemble à construire un avenir d’excellence.
          </p>

          <div class="about-feature-list">
            <div class="about-feature">
              <span class="about-feature__icon about-feature__icon--blue"><i class="bi bi-people-fill"></i></span>
              <div>
                <h3>Renouer les liens entre anciens étudiants</h3>
                <p>Une communauté unie, hier, aujourd’hui et demain.</p>
              </div>
            </div>

            <div class="about-feature">
              <span class="about-feature__icon about-feature__icon--green"><i class="bi bi-diagram-3-fill"></i></span>
              <div>
                <h3>Créer un réseau solide</h3>
                <p>Des opportunités humaines et professionnelles.</p>
              </div>
            </div>

            <div class="about-feature">
              <span class="about-feature__icon about-feature__icon--blue"><i class="bi bi-mortarboard-fill"></i></span>
              <div>
                <h3>Promouvoir l’excellence académique</h3>
                <p>Soutenir les talents d’aujourd’hui et de demain.</p>
              </div>
            </div>

            <div class="about-feature">
              <span class="about-feature__icon about-feature__icon--green"><i class="bi bi-calendar-event-fill"></i></span>
              <div>
                <h3>Organiser des événements</h3>
                <p>Des moments d’échange, de partage et d’inspiration.</p>
              </div>
            </div>
          </div>

          <div class="about-cta-row">
            <a href="#community" class="about-cta">
              Découvrir l’association <i class="bi bi-arrow-right"></i>
            </a>
            <div class="about-family-note">
              <span>Plus qu’un réseau,</span>
              <strong>une même famille.</strong>
            </div>
          </div>

                  </div>
      </div>

      <!-- RIGHT GLOBE -->
      <div class="col-lg-6">
        <div class="globe-wrapper" data-aos="fade-left">
          <div class="globe-halo" aria-hidden="true"></div>
          <div class="globe-orbit globe-orbit--one" aria-hidden="true"></div>
          <div class="globe-orbit globe-orbit--two" aria-hidden="true"></div>

          <div class="globe-container">
            <img src="{{ asset('front/assets/img/globe3.png') }}" class="globe-map" alt="Digital Globe">

                        <!-- Network SVG -->
                        <svg class="connection-svg" viewBox="0 0 600 600">

    <!-- LINES (réseau dense) -->
    <!-- cercle externe -->
    <line x1="120" y1="180" x2="200" y2="120"/>
    <line x1="200" y1="120" x2="300" y2="100"/>
    <line x1="300" y1="100" x2="400" y2="130"/>
    <line x1="400" y1="130" x2="480" y2="220"/>
    <line x1="480" y1="220" x2="520" y2="320"/>
    <line x1="520" y1="320" x2="460" y2="420"/>
    <line x1="460" y1="420" x2="350" y2="500"/>
    <line x1="350" y1="500" x2="220" y2="480"/>
    <line x1="220" y1="480" x2="140" y2="400"/>
    <line x1="140" y1="400" x2="100" y2="300"/>
    <line x1="100" y1="300" x2="120" y2="180"/>

    <!-- connexions internes (densification réseau) -->
    <line x1="120" y1="180" x2="300" y2="100"/>
    <line x1="120" y1="180" x2="460" y2="420"/>
    <line x1="200" y1="120" x2="520" y2="320"/>
    <line x1="300" y1="100" x2="350" y2="500"/>
    <line x1="400" y1="130" x2="140" y2="400"/>
    <line x1="480" y1="220" x2="220" y2="480"/>
    <line x1="520" y1="320" x2="120" y2="180"/>
    <line x1="460" y1="420" x2="200" y2="120"/>
    <line x1="350" y1="500" x2="100" y2="300"/>
    <line x1="220" y1="480" x2="400" y2="130"/>

    <!-- NODES BLEUS (plus nombreux) -->
    <circle class="node blue" cx="120" cy="180" r="5"/>
    <circle class="node green" cx="240" cy="180" r="4"/>
    <circle class="node blue" cx="300" cy="100" r="5"/>
    <circle class="node green" cx="310" cy="220" r="4"/>
    <circle class="node blue" cx="480" cy="220" r="5"/>
    <circle class="node green" cx="390" cy="380" r="4"/>
    <circle class="node blue" cx="460" cy="420" r="5"/>
    <circle class="node green" cx="200" cy="420" r="4"/>
    <circle class="node blue" cx="220" cy="480" r="5"/>
    <circle class="node green" cx="260" cy="340" r="4"/>
    <circle class="node blue" cx="140" cy="400" r="5"/>
    <circle class="node green" cx="420" cy="200" r="4"/>
    <circle class="node blue" cx="260" cy="260" r="5"/>

    <!-- NODES VERTS (densifiés aussi) -->
    <circle class="node green" cx="170" cy="220" r="4"/>
    <circle class="node blue" cx="200" cy="120" r="5"/>
    <circle class="node green" cx="380" cy="260" r="4"/>
    <circle class="node blue" cx="400" cy="130" r="5"/>
    <circle class="node green" cx="430" cy="300" r="4"/>
    <circle class="node blue" cx="520" cy="320" r="5"/>
    <circle class="node green" cx="300" cy="420" r="4"/>
    <circle class="node blue" cx="350" cy="500" r="5"/>
    <circle class="node green" cx="180" cy="320" r="4"/>
    <circle class="node blue" cx="100" cy="300" r="5"/>
    <circle class="node green" cx="320" cy="380" r="4"/>
    <circle class="node blue" cx="340" cy="320" r="5"/>
    <circle class="node green" cx="250" cy="280" r="4"/>
    <circle class="node green" cx="360" cy="240" r="4"/>

</svg>
          </div>

 
          <div class="about-world-note">
            Des Enimiens<br>
            <span>aux quatre coins du monde</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- /About Section -->
    <!-- Team Carousel Section -->
<!-- Team Carousel Section -->
@if ($boardMembers->isNotEmpty())
<section id="team-carousel" class="team-section">
  <div class="container" data-aos="fade-up">
  
<div class="section-title" data-aos="fade-up">
    <span class="section-subtitle">DÉCOUVREZ NOTRE ÉQUIPE</span>
    <h2 class="title">Bureau exécutif</h2>
  </div>
    <div class="team-heading-row">
      <div class="team-heading-copy">
        
       <p>Une équipe engagée au service de la communauté</p>
      </div>

      <a href="{{ route('front.bureau') }}" class="team-view-all">
        Voir toute l'équipe <span aria-hidden="true">→</span>
      </a>
    </div>

    <div class="team-carousel-shell">
      <div class="carousel-wrapper swiper">
        <div class="team-carousel-track swiper-wrapper">
          @php
            // Le carrousel tourne en boucle (3 cartes visibles) : il faut au moins 6 cartes.
            $slides = $boardMembers;
            while ($slides->isNotEmpty() && $slides->count() < 6) {
                $slides = $slides->concat($boardMembers);
            }
          @endphp
          @foreach ($slides as $i => $member)
            <article class="team-member swiper-slide" @if ($i >= $boardMembers->count()) aria-hidden="true" @endif>
              <div class="member-image">
                <img src="{{ $member->photoUrl() }}" alt="{{ $member->full_name }}" loading="lazy">
              </div>
              <div class="member-info">
                <p class="role">{{ $member->role }}</p>
                <h3>{{ $member->full_name }}</h3>
                @if ($member->displayQuote())
                  <p class="description">{{ $member->displayQuote() }}</p>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      </div>

      <div class="carousel-navigation" aria-hidden="false">
        <button class="carousel-btn prev" type="button" aria-label="Membre précédent">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-btn next" type="button" aria-label="Membre suivant">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <div class="swiper-pagination team-section-pagination" aria-label="Progression du carrousel du bureau exécutif"></div>
    </div>

  </div>
</section>
@endif




<section id="actu" aria-labelledby="actu-title">
  <div class="actu-header">
    <div class="actu-header-left">
      <div class="section-title" data-aos="fade-up">
        <span class="section-subtitle">SUIVEZ NOS</span>
        <h2 class="title" id="actu-title">Actualités</h2>
      </div>
      <p>Restez informé des dernières nouvelles de l'ASENIM et de l'ENIM</p>
    </div>
 <a href="{{ route('front.news') }}" class="all-news">
      Voir toutes les actualités <span aria-hidden="true">→</span>
    </a>
   
  </div>

  <div class="actu-main-layout">
    <div class="actu-carousel-shell">
      <div class="actu-swiper swiper">
        <div class="actu-track swiper-wrapper">
          @forelse ($latestNews as $item)
            @php $d = \Carbon\Carbon::parse($item->date); @endphp
            <article class="actu-card swiper-slide">
              <div class="actu-image">
                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('front/assets/img/about.jpg') }}" alt="" loading="lazy">
                <div class="actu-date"><strong>{{ $d->format('d') }}</strong><span>{{ \Illuminate\Support\Str::ucfirst($d->translatedFormat('M Y')) }}</span></div>
              </div>
              <div class="actu-content">
                <h3>{{ $item->title }}</h3>
                <p>{{ \App\Support\RichText::excerpt($item->content, 120) }}</p>
                <a href="{{ route('front.news.show', $item) }}">Lire la suite <span aria-hidden="true">→</span></a>
              </div>
            </article>
          @empty
            <article class="actu-card swiper-slide">
              <div class="actu-image">
                <img src="{{ asset('front/assets/img/about.jpg') }}" alt="">
              </div>
              <div class="actu-content">
                <h3>Bientôt de nouvelles actualités</h3>
                <p>Les prochaines nouvelles de l'ASENIM et de l'ENIM seront publiées ici.</p>
              </div>
            </article>
          @endforelse
        </div>
      </div>

      <div class="actu-navigation">
        <button class="actu-carousel-btn prev" type="button" aria-label="Actualité précédente">
          <i class="fas fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="actu-carousel-btn next" type="button" aria-label="Actualité suivante">
          <i class="fas fa-chevron-right" aria-hidden="true"></i>
        </button>
      </div>

      <div class="swiper-pagination actu-pagination" aria-label="Progression du carrousel des actualités"></div>
    </div>

    <aside class="actu-gallery">
      <div class="gallery-title">
        <div class="gallery-icon" aria-hidden="true"><i class="bi bi-images"></i></div>
        <div>
          <h3>Galerie</h3>
          <p>Revivez nos événements en images</p>
        </div>
      </div>

      <div class="gallery-images">
        <div class="gallery-main">
          <img src="{{ asset('front/assets/img/about.jpg') }}" alt="Galerie ASENIM">
        </div>
        <div class="gallery-small">
          <img src="{{ asset('front/assets/img/about.jpg') }}" alt="ENIM">
          <img src="{{ asset('front/assets/img/clients/client-1.png') }}" alt="Événement ASENIM">
        </div>
      </div>

      <div class="gallery-footer">
        <a href="{{ route('front.gallery') }}" class="gallery-btn" aria-label="Voir la galerie">→</a>
        <span class="gallery-text">Des souvenirs<br>qui nous rassemblent !</span>
      </div>
    </aside>
  </div>
</section>



<div id="community">

  <div class="community-container">

    <div class="community-title" data-aos="fade-up">
      <span class="title-line"></span>

      <div>
	  <span class="section-subtitle">REJOIGNER NOTRE</span>
        <h2>Communauté Enimienne</h2>
        <p>Une plateforme d’échange et de networking pour tous les Enimiens</p>
      </div>
    </div>


    <div class="community-content">

      <!-- ORDINATEUR + TELEPHONE -->
      <div class="community-visual">

        <div class="visual-blob"></div>

        <div class="mini-note" data-aos="zoom-in-up" data-aos-easing= "linear" data-aos-delay="200">
          <span>Échangez</span>
          <span>Partagez</span>
          <span>Collaborez</span>
          <span>Grandissez ensemble</span>
        </div>

        <div class="devices-wrap">

          <!-- Laptop -->
          <div class="laptop" data-aos="zoom-in-up" data-aos-easing= "linear" data-aos-delay="600">

            <div class="laptop-screen" >

              <div class="screen-topbar">
                <span></span>
                <span></span>
                <span></span>
              </div>

              <div class="screen-body" >

                <div class="sidebar">

                  <div class="logo-box"></div>

                  <div class="side-line"></div>
                  <div class="side-line short"></div>
                  <div class="side-line"></div>
                  <div class="side-line short"></div>

                </div>

                <div class="main-ui">

                  <div class="main-header">
                    <div class="search-bar"></div>
                    <div class="avatar"></div>
                  </div>

                  <div class="content-card">
                    <div class="card-title"></div>
                    <div class="card-line"></div>
                    <div class="card-line short"></div>
                  </div>

                  <div class="content-list">

                    <div class="list-item">
                      <div class="bullet"></div>
                      <div class="list-text">
                        <div class="line"></div>
                        <div class="line short"></div>
                      </div>
                    </div>

                    <div class="list-item">
                      <div class="bullet"></div>
                      <div class="list-text">
                        <div class="line"></div>
                        <div class="line short"></div>
                      </div>
                    </div>

                    <div class="list-item">
                      <div class="bullet"></div>
                      <div class="list-text">
                        <div class="line"></div>
                        <div class="line short"></div>
                      </div>
                    </div>

                  </div>

                </div>

              </div>

            </div>

            <div class="laptop-base"></div>

          </div>


          <!-- Téléphone -->
          <div class="phone" data-aos="fade-up" data-aos-easing= "linear" data-aos-delay="900">

            <div class="phone-notch"></div>

            <div class="phone-screen">

              <div class="phone-top"></div>

              <div class="phone-card"></div>

              <div class="phone-item">
                <div class="phone-circle"></div>

                <div class="phone-lines">
                  <div class="pline"></div>
                  <div class="pline short"></div>
                </div>
              </div>

              <div class="phone-item">
                <div class="phone-circle"></div>

                <div class="phone-lines">
                  <div class="pline"></div>
                  <div class="pline short"></div>
                </div>
              </div>

              <div class="phone-item">
                <div class="phone-circle"></div>

                <div class="phone-lines">
                  <div class="pline"></div>
                  <div class="pline short"></div>
                </div>
              </div>

            </div>

          </div>

        </div>

        <div class="yellow-arrow" data-aos="zoom-in-up" data-aos-easing= "linear" data-aos-delay="300">↘</div>

      </div>


      <!-- fonctionnalités -->
      <div class="community-features" data-aos="fade-up" data-aos-easing= "linear" data-aos-delay="1300">

        <div class="feature-item">

          <div class="feature-icon"> <i class="bi bi-wechat"></i></div>

          <div>
            <h3>Communication instantanée</h3>

            <p>
              Échangez avec les alumni <br>
              et enseignants.
            </p>
          </div>

        </div>


        <div class="feature-item">

          <div class="feature-icon"><i class="bi bi-ubuntu"></i><!--<img src="{{ asset('front/assets/img/team-logo-design5.png') }}" class="img-fluid" alt="" >--></div>

          <div>
            <h3>Travail d’équipe</h3>

            <p>
              Collaborez sur des projets<br>
              et des opportunités.
            </p>
          </div>

        </div>


        <div class="feature-item">

          <div class="feature-icon"><i class="bi bi-image"></i></div>

          <div>
            <h3>Partage multimédia</h3>

            <p>
              Partagez vos idées, documents<br>
              et expériences.
            </p>
          </div>

        </div>


        <div class="feature-item">

          <div class="feature-icon"><!--🔒--> <i class="bi bi-shield-lock flex-shrink-0"></i></div>

          <div>
            <h3>Sécurité et confidentialité</h3>

            <p>
              Un espace sûr et réservé<br>
              à la communauté Enimienne.
            </p>
          </div>

        </div>

      </div>


      <!-- droite -->
      <div class="community-action" data-aos="fade-up" data-aos-easing= "linear" data-aos-delay="1300">

        <a href="{{ route('front.register') }}" class="community-button">
          Rejoindre la communauté
          <span>→</span>
        </a>

        <div class="handwritten" data-aos="fade-up" data-aos-easing= "linear" data-aos-delay="1350">
          Un réseau,<br>
          des opportunités<br>
          infinies !

          <span class="hand-line"></span>
        </div>

      </div>

    </div>

  </div>

</div>





  </main>
<section id="Form_Contact">
  <!-- Image de fond modifiable directement depuis le HTML -->
  <img class="form-contact-bg" src="{{ asset('front/assets/img/back31.png') }}" alt="" aria-hidden="true">

  <div class="contact-card">

    <!-- GOOGLE MAPS -->
    <div class="contact-map" aria-label="Localisation ASENIM">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3237.4075363136444!2d10.804279099999999!3d35.7653643!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x130212a893b893ef%3A0x3697457a7a81fcaf!2s%C3%89cole%20nationale%20d'ing%C3%A9nieurs%20de%20Monastir!5e0!3m2!1sfr!2stn!4v1762865955044!5m2!1sfr!2stn"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    </div>

    <div class="contact-info">
      <div class="info-line">
        <span class="icon">
          <i class="fa fa-map-marker" aria-hidden="true"></i>
        </span>
        <p>{{ $contact['address_inline'] }}</p>
      </div>

      <div class="info-line">
        <span class="icon">
          <i class="fa-solid fa-envelope"></i>
        </span>
        <p><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></p>
      </div>

      <div class="info-line">
        <span class="icon">
          <i class="fa fa-phone" aria-hidden="true"></i>
        </span>
        <p><a href="tel:{{ $contact['phone_href'] }}">{{ $contact['phone'] }}</a></p>
      </div>

      @if (count($contact['socials']))
      <div class="socials">
        @foreach ($contact['socials'] as $social)
            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }} (nouvel onglet)" title="{{ $social['label'] }}"><span><i class="{{ $social['icon'] }}" aria-hidden="true"></i></span></a>
        @endforeach
      </div>
      @endif
    </div>

    <div class="contact-form">
      <h2>NEWSLETTER</h2>

      <p>
        Recevez nos dernières actualités en vous abonnant à notre newsletter.
      </p>

      <form data-newsletter-form>
        <div class="form-group">
          <input
            type="email"
            id="email"
            name="email"
            placeholder=" "
            required
          >
          <label for="email">Votre Email</label>
        </div>

        <button type="submit">
          <i class="fa fa-paper-plane" aria-hidden="true"></i>
          S'ABONNER
        </button>
      </form>
    </div>

  </div>
</section>


   <section id="clients" class="clients light-background">
        <div class="clients-wrapper" data-aos="fade-up">
            <!-- Texte à gauche -->
            <div class="clients-text ml11">
                <span class="text-wrapper">
                    <span class="line line1" aria-hidden="true"></span>
                    <span class="letters">Nos partenaires</span>
                </span>
            </div>

            <!-- Zone des logos avec flèches -->
            <div class="clients-carousel">
               <button class="arrow left" id="prevBtn">
                    <i class="fa fa-chevron-left"></i>
                </button>

                <div class="clients-logos" id="clientsLogos">
                    <!-- Logos originaux -->
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/ANRP2.png') }}" alt="ANRP2"></div>
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/logo-enim.png') }}" alt="ENIM"></div>
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/logo-leste.png') }}" alt="LESTE"></div>
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/logo-UM.png') }}" alt="UM"></div>
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/Cettex.png') }}" alt="Cettex"></div>
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/logo_enit.png') }}" alt="enit"></div>
                    <div class="client-logo"><img src="{{ asset('front/assets/img/clients/icmic01.png') }}" alt="icmic01"></div>
                    <!-- Logos dupliqués pour l'effet infini (ajoutés par JavaScript) -->
                </div>

                <button class="arrow right" id="nextBtn">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>
@endsection

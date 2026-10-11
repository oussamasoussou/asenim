{{--
    Gabarit du site public ASENIM.
    Variables partagées (FrontComposer) : $contact (adresse, téléphone, e-mail issus de l'admin « Coordonnées »)
--}}
@php
    $nav = [
        ['front.home', 'Accueil', ['front.home']],
        ['front.about', 'Qui sommes-nous', ['front.about']],
        ['front.bureau', 'Bureau exécutif', ['front.bureau']],
        ['front.news', 'Actualités', ['front.news', 'front.news.show']],
        ['front.gallery', 'Galerie', ['front.gallery', 'front.icmic']],
        ['front.contact', 'Contact', ['front.contact']],
    ];
    $isHome = request()->routeIs('front.home');
@endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') — @endif ASENIM</title>
    <meta name="description" content="@yield('description', 'ASENIM — Association Scientifique de l’ENIM : réseau des diplômés, étudiants et enseignants de l’École nationale d’ingénieurs de Monastir.')">

    <link href="{{ asset('front/assets/img/ASE.ico') }}" rel="icon">
    <link href="{{ asset('front/assets/img/ASE.ico') }}" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="{{ asset('front/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('front/assets/css/main.css') }}?v={{ filemtime(public_path('front/assets/css/main.css')) }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/front-app.css') }}?v={{ filemtime(public_path('front/assets/css/front-app.css')) }}" rel="stylesheet">

    {{-- Configuration lue par main.js (URL réelles, état de connexion) --}}
    <script>
        window.ASENIM = {
            assetBase: @json(asset('front/assets')) + '/',
            authenticated: @json(auth()->check()),
            dashboardUrl: @json(route('index')),
            loginUrl: @json(route('login')),
            registerUrl: @json(route('front.register.store')),
            forgotUrl: @json(route('password.request')),
            homeUrl: @json(route('front.home')),
            routes: {
                about: @json(route('front.about')),
                bureau: @json(route('front.bureau')),
                news: @json(route('front.news')),
            },
            isHome: @json($isHome),
        };
    </script>
</head>

<body class="@yield('body_class', 'starter-page-page inner-page')">

    <header id="header" class="header d-flex align-items-center fixed-top w-100">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="{{ route('front.home') }}" class="logo d-flex align-items-center">
                <img id="logo-img" src="{{ asset($isHome ? 'front/assets/img/LOGO esim petit.png' : 'front/assets/img/LOGO esim petit2.png') }}" alt="ASENIM — accueil">
            </a>

            <nav id="navmenu" class="navmenu" aria-label="Navigation principale">
                <ul class="d-flex align-items-center justify-content-center">
                    @foreach ($nav as [$routeName, $label, $match])
                        <li><a href="{{ route($routeName) }}" class="{{ request()->routeIs(...$match) ? 'active' : '' }}"
                               @if (request()->routeIs(...$match)) aria-current="page" @endif>{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <div class="header-right d-flex align-items-center">
                <div class="user-dropdown">
                    @auth
                        <a class="user-btn user-btn--auth" href="{{ route('index') }}"
                           aria-label="Accéder à mon espace membre" title="Mon espace membre">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                        </a>
                    @else
                        <button class="user-btn" type="button" aria-label="Ouvrir l’espace membre"
                                aria-haspopup="dialog" aria-controls="asenimAuthModal">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                        </button>
                    @endauth
                </div>

                <button class="white-hamburger asenim-hamburger" type="button" aria-label="Ouvrir le menu"
                        aria-controls="menu-overlay" aria-expanded="false">
                    <span class="asenim-hamburger__line"></span>
                    <span class="asenim-hamburger__line"></span>
                    <span class="asenim-hamburger__line"></span>
                </button>
            </div>
        </div>
    </header>

    @yield('content')

    @unless (View::hasSection('hide_footer'))
        <footer class="asenim-footer">
            <div class="asenim-footer__main">
                <div class="asenim-footer__container">

                    <div class="asenim-footer__brand">
                        <a href="{{ route('front.home') }}" class="asenim-footer__logo" aria-label="ASENIM — Accueil">
                            <img src="{{ asset('front/assets/img/LOGO esim petit.png') }}" alt="Logo ASENIM">
                        </a>
                        <p>Association Scientifique de l'ENIM<br>Une communauté au service de l'excellence</p>
                    </div>

                    <div class="asenim-footer__contact" aria-label="Coordonnées ASENIM">
                        <p><i class="bi bi-geo-alt"></i><a href="{{ $contact['map_url'] }}" target="_blank" rel="noopener">{!! nl2br(e($contact['address'])) !!}</a></p>
                        <p><i class="bi bi-envelope"></i><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></p>
                        <p><i class="bi bi-telephone"></i><a href="tel:{{ $contact['phone_href'] }}">{{ $contact['phone'] }}</a></p>
                    </div>

                    <div class="asenim-footer__newsletter">
                        <h3>Newsletter</h3>
                        <p>Restez informé de nos actualités</p>
                        <form action="#" method="post" class="asenim-footer__form" data-newsletter-form>
                            <label class="visually-hidden" for="footer-email">Votre adresse e-mail</label>
                            <input id="footer-email" type="email" name="email" placeholder="Votre adresse e-mail" required>
                            <button type="submit">S'abonner</button>
                        </form>
                    </div>

                    <div class="asenim-footer__motto" aria-label="Devise ASENIM">
                        <span>Toujours plus loin</span>
                        <span>ensemble !</span>
                        <i aria-hidden="true"></i>
                    </div>
                </div>
            </div>

            <div class="asenim-footer__bottom">
                <div class="asenim-footer__container asenim-footer__bottom-inner">
                    <p>© {{ date('Y') }} ASENIM. Tous droits réservés.</p>

                    <div class="asenim-footer__bottom-right">
                        <nav class="asenim-footer__legal" aria-label="Liens utiles">
                            <a href="{{ route('front.contact') }}">Contact</a>
                            <span>|</span>
                            @auth
                                <a href="{{ route('index') }}">Espace membre</a>
                            @else
                                <a href="{{ route('login') }}">Espace membre</a>
                            @endauth
                        </nav>

                        @if (count($contact['socials']))
                            <div class="asenim-footer__social" aria-label="Réseaux sociaux">
                                @foreach ($contact['socials'] as $social)
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }} (nouvel onglet)" title="{{ $social['label'] }}"><i class="{{ $social['icon'] }}" aria-hidden="true"></i></a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    @endunless

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center" aria-label="Revenir en haut"><i class="bi bi-arrow-up-short"></i></a>

    {{-- Menu plein écran --}}
    <div id="menu-overlay" class="menu-overlay">
        <div class="menu-container">
            <button id="close-menu" class="close-menu" aria-label="Fermer le menu">
                <div class="x-icon">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                </div>
            </button>

            <div class="menu-content">
                <div class="menu-column">
                    <h3 class="menu-section-title">Découvrir Asenim</h3>
                    <ul class="menu-list">
                        @foreach ($nav as [$routeName, $label, $match])
                            <li><a href="{{ route($routeName) }}" class="{{ request()->routeIs(...$match) ? 'active' : '' }}">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="menu-column">
                    <h3 class="menu-section-title">Nous contacter</h3>
                    <div class="contact-info">
                        <p><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></p>
                        <p><a href="tel:{{ $contact['phone_href'] }}">{{ $contact['phone'] }}</a></p>
                        <p class="contact-divider"></p>
                        @auth
                            <a href="{{ route('index') }}" class="join-btn btn btn-primary d-inline-flex">MON ESPACE <i class="bi bi-arrow-up-right"></i></a>
                        @else
                            <button type="button" class="join-btn btn btn-primary d-inline-flex" data-auth-open>REJOIGNEZ-NOUS <i class="bi bi-arrow-up-right"></i></button>
                        @endauth
                    </div>

                    @if (count($contact['socials']))
                        <h3 class="menu-section-title stay-connected">Restez connecté</h3>
                        <div class="social-links">
                            @foreach ($contact['socials'] as $social)
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }} (nouvel onglet)" title="{{ $social['label'] }}"><i class="{{ $social['icon'] }}" aria-hidden="true"></i></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="menu-footer">
                <p>&copy; {{ date('Y') }} ASENIM. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('front/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('front/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    @if ($isHome)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    @endif
    <script src="{{ asset('front/assets/js/main.js') }}?v={{ filemtime(public_path('front/assets/js/main.js')) }}"></script>
    @stack('scripts')
</body>

</html>

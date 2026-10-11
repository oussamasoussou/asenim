<!DOCTYPE html>
<html lang="fr" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@hasSection('title')@yield('title') · @endif{{ 'Asenim' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ filemtime(public_path('assets/css/app.css')) }}" />
    @stack('styles')

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    @php
        $authUser = Auth::user();
        $nav = [
            ['label' => 'Tableau de bord', 'icon' => 'bx-grid-alt', 'route' => 'index', 'match' => ['index', 'membre']],
        ];
        if ($authUser && $authUser->isAdmin()) {
            $nav[] = ['section' => 'Administration'];
            $pendingCount = \App\Models\User::pending()->count();
            $unreadMessages = \App\Models\ContactMessage::unread()->count();
            $nav[] = ['label' => 'Utilisateurs', 'icon' => 'bx-group', 'route' => 'users.index', 'match' => ['users.index', 'users.archives', 'users.pending', 'store-user', 'users.edit', 'users.edit.*'], 'badge' => $pendingCount, 'badge_label' => 'inscription(s) en attente'];
            $nav[] = ['label' => 'Messages', 'icon' => 'bx-envelope', 'route' => 'messages.index', 'match' => ['messages.*'], 'badge' => $unreadMessages, 'badge_label' => 'message(s) non lu(s)'];
            $nav[] = ['label' => 'Bureau exécutif', 'icon' => 'bx-id-card', 'route' => 'board.index', 'match' => ['board.*']];
            $nav[] = ['label' => 'Coordonnées', 'icon' => 'bx-buildings', 'route' => 'company.edit', 'match' => ['company.*']];
        }
        $nav[] = ['section' => 'Espace de travail'];
        $nav[] = ['label' => 'Documents', 'icon' => 'bx-folder', 'route' => 'documents.non_archived', 'match' => ['documents.*', 'store-document']];
        $nav[] = ['label' => 'Actualités & événements', 'icon' => 'bx-news', 'route' => 'news.index', 'match' => ['news.*', 'store-news']];
        $nav[] = ['label' => 'Messagerie', 'icon' => 'bx-chat', 'route' => 'chat.index', 'match' => ['chat.*']];

        $currentSection = collect($nav)->first(fn ($i) => isset($i['match']) && request()->routeIs(...$i['match']));
    @endphp

    <a href="#main" class="sr-only">Aller au contenu</a>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.sidebar', ['nav' => $nav, 'authUser' => $authUser])

            <div class="layout-page">
                @include('layouts.nav', ['authUser' => $authUser, 'currentSection' => $currentSection])

                <div class="content-wrapper">
                    <main id="main" class="app-content">
                        @yield('content')
                    </main>
                    @include('layouts.footer')
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    {{-- Modale de confirmation partagée (déclenchée par [data-confirm]) --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <form method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="modal-body pt-4">
                    <div class="d-flex gap-3">
                        <span class="confirm-icon is-danger" data-confirm-icon><i class="bx bx-error"></i></span>
                        <div class="pt-1">
                            <h2 class="modal-title mb-1" id="confirmModalTitle" data-confirm-title>Confirmer l’action</h2>
                            <p class="text-muted mb-0" data-confirm-text></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer</button>
                </div>
            </form>
        </div>
    </div>

    @php
        $flash = array_filter([
            'success' => session('success'),
            'status' => session('status'),
            'error' => session('error') ?: ($errors->any() ? 'Certains champs sont invalides. Corrigez-les puis réessayez.' : null),
        ]);
    @endphp
    <script type="application/json" id="ui-flash">@json($flash)</script>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>

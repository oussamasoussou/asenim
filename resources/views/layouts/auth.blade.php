<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') · Asenim</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ filemtime(public_path('assets/css/app.css')) }}" />
</head>

<body>
    <div class="auth-shell">
        <aside class="auth-aside" aria-hidden="true">
            <x-brand :href="route('login')" inverse tabindex="-1" />
            <div>
                <h2>L’espace de travail des membres, réuni au même endroit.</h2>
                <p class="mb-4">Documents partagés, actualités, agenda et messagerie de l’équipe.</p>
                <div class="auth-feature"><i class="bx bx-folder"></i><span>Accédez aux ressources selon votre statut.</span></div>
                <div class="auth-feature"><i class="bx bx-calendar-event"></i><span>Suivez les événements à venir.</span></div>
                <div class="auth-feature"><i class="bx bx-chat"></i><span>Échangez en temps réel avec les membres.</span></div>
            </div>
            <p class="small mb-0">© {{ date('Y') }} Asenim</p>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                <x-brand :href="route('login')" class="d-lg-none" />
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
</body>

</html>

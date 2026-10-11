<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') · Asenim</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
</head>

<body>
    <main class="error-shell">
        <div style="max-width: 460px;">
            <div class="empty-state-icon" style="width:64px;height:64px;font-size:2rem;"><i class="bx @yield('icon', 'bx-error')"></i></div>
            <p class="error-code">Erreur @yield('code')</p>
            <h1 class="error-title">@yield('heading')</h1>
            <p class="text-muted mb-4">@yield('message')</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="javascript:history.back()" class="btn btn-secondary"><i class="bx bx-arrow-back"></i>Page précédente</a>
                <a href="{{ url('/') }}" class="btn btn-primary"><i class="bx bx-home"></i>Accueil</a>
            </div>
        </div>
    </main>
</body>

</html>

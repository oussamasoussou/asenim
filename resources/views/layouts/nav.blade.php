@php
    $authUser = $authUser ?? Auth::user();
    $currentSection = $currentSection ?? null;
@endphp
<nav class="layout-navbar navbar app-topbar d-flex align-items-center" id="layout-navbar" aria-label="Barre supérieure">
    <div class="layout-menu-toggle d-xl-none me-2">
        <a class="topbar-icon-btn" href="javascript:void(0)" aria-label="Ouvrir le menu">
            <i class="bx bx-menu"></i>
        </a>
    </div>

    <div class="min-w-0 d-none d-sm-block">
        @if ($currentSection)
            <p class="topbar-title text-truncate">{{ $currentSection['label'] }}</p>
        @else
            <p class="topbar-title">Mon compte</p>
        @endif
    </div>

    <div class="d-flex align-items-center gap-2 ms-auto">
        <a href="{{ route('front.home') }}" class="btn btn-ghost btn-sm d-none d-md-inline-flex" target="_blank" rel="noopener" title="Ouvrir le site public dans un nouvel onglet">
            <i class="bx bx-globe"></i>Voir le site
        </a>
        <a href="{{ route('chat.index') }}" class="topbar-icon-btn" aria-label="Messagerie" title="Messagerie">
            <i class="bx bx-chat"></i>
        </a>

        @if ($authUser)
            <div class="dropdown">
                <button class="topbar-user" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu du compte">
                    <x-avatar :user="$authUser" size="sm" />
                    <span class="d-none d-md-inline fw-semibold small">{{ $authUser->first_name }}</span>
                    <i class="bx bx-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header-user">
                        <x-avatar :user="$authUser" />
                        <div class="min-w-0">
                            <div class="fw-semibold text-body-strong text-truncate">{{ $authUser->first_name }} {{ $authUser->last_name }}</div>
                            <div class="text-muted text-xs text-truncate">{{ $authUser->email }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('users.editConnectedUser') }}"><i class="bx bx-user-circle"></i>Mon profil</a></li>
                    <li><a class="dropdown-item" href="{{ route('users.edit.security') }}"><i class="bx bx-lock-alt"></i>Sécurité</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" data-no-loading>
                            @csrf
                            <button type="submit" class="dropdown-item is-danger w-100"><i class="bx bx-log-out"></i>Se déconnecter</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>

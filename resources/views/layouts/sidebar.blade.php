<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" aria-label="Navigation principale">
    <div class="app-brand">
        <x-brand />
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link ms-auto d-xl-none topbar-icon-btn" aria-label="Fermer le menu">
            <i class="bx bx-x"></i>
        </a>
    </div>

    <ul class="menu-inner">
        @foreach ($nav as $item)
            @if (isset($item['section']))
                <li class="menu-section">{{ $item['section'] }}</li>
            @else
                @php $active = request()->routeIs(...$item['match']); @endphp
                <li class="menu-item {{ $active ? 'active' : '' }}">
                    <a href="{{ route($item['route']) }}" class="menu-link" @if ($active) aria-current="page" @endif>
                        <i class="menu-icon bx {{ $item['icon'] }}"></i>
                        <div>{{ $item['label'] }}</div>
                        @if (!empty($item['badge']))
                            <span class="menu-badge is-alert" title="{{ $item['badge'] }} {{ $item['badge_label'] ?? '' }}">{{ $item['badge'] }}<span class="sr-only"> {{ $item['badge_label'] ?? '' }}</span></span>
                        @endif
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

    @if ($authUser)
        <div class="sidebar-footer">
            <a href="{{ route('users.editConnectedUser') }}" class="sidebar-user">
                <x-avatar :user="$authUser" size="sm" />
                <span class="min-w-0">
                    <span class="sidebar-user-name d-block text-truncate">{{ $authUser->first_name }} {{ $authUser->last_name }}</span>
                    <span class="sidebar-user-role">{{ $authUser->isAdmin() ? 'Administrateur' : 'Membre' }}</span>
                </span>
                <i class="bx bx-cog ms-auto text-muted"></i>
            </a>
        </div>
    @endif
</aside>

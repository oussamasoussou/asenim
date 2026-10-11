{{-- Onglets du profil : mode "self" (mon compte) ou "admin" (édition d'un utilisateur). --}}
@props(['active', 'user' => null])

@php
    $tabs = $user
        ? [
            'personnel' => ['Informations personnelles', 'bx-user', route('users.edit', $user->id)],
            'professionnelle' => ['Parcours professionnel', 'bx-briefcase', route('users.edit.professionnelle', $user->id)],
            'biography' => ['Biographie & activités', 'bx-book-content', route('users.edit.biography', $user->id)],
        ]
        : [
            'personnel' => ['Informations personnelles', 'bx-user', route('users.editConnectedUser')],
            'professionnelle' => ['Parcours professionnel', 'bx-briefcase', route('users.connected.edit.professionnelle')],
            'biography' => ['Biographie & activités', 'bx-book-content', route('users.connected.edit.biography')],
            'security' => ['Sécurité', 'bx-lock-alt', route('users.edit.security')],
        ];
@endphp

<nav class="tabs-underline" aria-label="Sections du profil">
    @foreach ($tabs as $key => [$label, $icon, $url])
        <a href="{{ $url }}" class="{{ $active === $key ? 'active' : '' }}" @if ($active === $key) aria-current="page" @endif>
            <i class="bx {{ $icon }}"></i>{{ $label }}
        </a>
    @endforeach
</nav>

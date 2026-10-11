{{-- Bascule entre les listes d'utilisateurs. Variable : $active (active|pending|archived) --}}
@php $pending = \App\Models\User::pending()->count(); @endphp
<nav class="segment-switch" aria-label="Filtrer les utilisateurs">
    <a href="{{ route('users.index') }}" class="{{ $active === 'active' ? 'active' : '' }}" @if ($active === 'active') aria-current="page" @endif>
        <i class="bx bx-user-check"></i>Actifs
    </a>
    <a href="{{ route('users.pending') }}" class="{{ $active === 'pending' ? 'active' : '' }}" @if ($active === 'pending') aria-current="page" @endif>
        <i class="bx bx-time-five"></i>En attente
        @if ($pending > 0)<span class="badge badge-danger ms-1">{{ $pending }}</span>@endif
    </a>
    <a href="{{ route('users.archives') }}" class="{{ $active === 'archived' ? 'active' : '' }}" @if ($active === 'archived') aria-current="page" @endif>
        <i class="bx bx-archive"></i>Archivés
    </a>
</nav>

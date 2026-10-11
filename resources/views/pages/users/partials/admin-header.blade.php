{{-- En-tête des pages d'édition d'un utilisateur (admin). Variable : $user --}}
<div class="page-header">
    <div class="page-header-main">
        <a href="{{ route('users.index') }}" class="page-back" aria-label="Retour à la liste"><i class="bx bx-arrow-back"></i></a>
        <div class="d-flex align-items-center gap-3 min-w-0">
            <x-avatar :user="$user" size="lg" :online="(bool) $user->user_connected" />
            <div class="min-w-0">
                <h1 class="page-title text-truncate">{{ $user->first_name }} {{ $user->last_name }}</h1>
                <p class="page-subtitle d-flex flex-wrap align-items-center gap-2">
                    @if ($user->role === 'admin')
                        <span class="badge badge-primary"><i class="bx bx-shield-quarter"></i>Administrateur</span>
                    @else
                        <span class="badge badge-neutral">Membre</span>
                    @endif
                    <span>{{ $user->email }}</span>
                </p>
            </div>
        </div>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-secondary"
            data-confirm="Le mot de passe de {{ $user->first_name }} {{ $user->last_name }} sera réinitialisé à la valeur par défaut. Pensez à le lui communiquer."
            data-title="Réinitialiser le mot de passe ?"
            data-action="{{ route('users.reset_password', $user->id) }}" data-method="POST"
            data-variant="warning" data-icon="bx-key" data-confirm-label="Réinitialiser">
            <i class="bx bx-key"></i>Réinitialiser le mot de passe
        </button>
        <button type="button" class="btn btn-soft-danger"
            data-confirm="{{ $user->first_name }} {{ $user->last_name }} perdra l’accès à la plateforme. Vous pourrez restaurer le compte depuis les archives."
            data-title="Archiver cet utilisateur ?"
            data-action="{{ route('users.destroy', $user->id) }}" data-method="DELETE"
            data-icon="bx-archive-in" data-confirm-label="Archiver">
            <i class="bx bx-archive-in"></i>Archiver
        </button>
    </div>
</div>

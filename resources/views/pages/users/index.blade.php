@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
    <x-page-header title="Utilisateurs" subtitle="Gérez les comptes, les rôles et les accès des membres de la plateforme.">
        <a href="{{ route('store-user') }}" class="btn btn-primary"><i class="bx bx-user-plus"></i>Nouvel utilisateur</a>
    </x-page-header>

    <div class="card table-card">
        <div class="card-header">
            <nav class="segment-switch" aria-label="Filtrer les utilisateurs">
                <a href="{{ route('users.index') }}" class="active" aria-current="page"><i class="bx bx-user-check"></i>Actifs</a>
                <a href="{{ route('users.archives') }}"><i class="bx bx-archive"></i>Archivés</a>
            </nav>
            <form action="{{ route('users.index') }}" method="GET" role="search" data-live-search data-target="#users-list" class="search-field">
                <i class="bx bx-search" aria-hidden="true"></i>
                <label for="search" class="sr-only">Rechercher un utilisateur</label>
                <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom, e-mail ou téléphone…" autocomplete="off">
                <span class="search-spinner" aria-hidden="true"></span>
            </form>
        </div>

        <div id="users-list" data-list-region>
            <div class="table-responsive">
                <table class="table table-hover table-stack">
                    <thead>
                        <tr>
                            <th scope="col"><x-sort-link column="first_name" label="Utilisateur" default="first_name" /></th>
                            <th scope="col"><x-sort-link column="email" label="Contact" /></th>
                            <th scope="col"><x-sort-link column="role" label="Rôle" /></th>
                            <th scope="col"><x-sort-link column="type" label="Statut" /></th>
                            <th scope="col" class="col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="cell-primary">
                                    <div class="user-cell">
                                        <x-avatar :user="$user" :online="(bool) $user->user_connected" />
                                        <div class="min-w-0">
                                            <a href="{{ route('users.edit', $user->id) }}" class="user-cell-name d-block text-truncate">{{ $user->first_name }} {{ $user->last_name }}</a>
                                            <div class="user-cell-meta">Inscrit {{ $user->created_at?->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Contact">
                                    <div>
                                        <div class="text-truncate" style="max-width: 260px;">
                                            <a href="mailto:{{ $user->email }}" class="text-body-strong">{{ $user->email }}</a>
                                        </div>
                                        <div class="user-cell-meta">{{ $user->phone ?: 'Téléphone non renseigné' }}</div>
                                    </div>
                                </td>
                                <td data-label="Rôle">
                                    @if ($user->role === 'admin')
                                        <span class="badge badge-primary"><i class="bx bx-shield-quarter"></i>Administrateur</span>
                                    @else
                                        <span class="badge badge-neutral">Membre</span>
                                    @endif
                                </td>
                                <td data-label="Statut">
                                    @if ($user->type === 'non_permanent')
                                        <span class="badge badge-info badge-dot">Non permanent</span>
                                    @else
                                        <span class="badge badge-violet badge-dot">Permanent</span>
                                    @endif
                                </td>
                                <td class="col-actions">
                                    <div class="row-actions">
                                        <a href="{{ route('users.edit', $user->id) }}" class="icon-action is-primary" title="Modifier" aria-label="Modifier {{ $user->first_name }} {{ $user->last_name }}">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <button type="button" class="icon-action" title="Réinitialiser le mot de passe" aria-label="Réinitialiser le mot de passe"
                                            data-confirm="Le mot de passe de {{ $user->first_name }} {{ $user->last_name }} sera réinitialisé à la valeur par défaut. Pensez à le lui communiquer."
                                            data-title="Réinitialiser le mot de passe ?"
                                            data-action="{{ route('users.reset_password', $user->id) }}" data-method="POST"
                                            data-variant="warning" data-icon="bx-key" data-confirm-label="Réinitialiser">
                                            <i class="bx bx-key"></i>
                                        </button>
                                        <button type="button" class="icon-action is-danger" title="Archiver" aria-label="Archiver"
                                            data-confirm="{{ $user->first_name }} {{ $user->last_name }} perdra l’accès à la plateforme. Vous pourrez restaurer le compte depuis les archives."
                                            data-title="Archiver cet utilisateur ?"
                                            data-action="{{ route('users.destroy', $user->id) }}" data-method="DELETE"
                                            data-icon="bx-archive-in" data-confirm-label="Archiver">
                                            <i class="bx bx-archive-in"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="cell-empty">
                                    @if (request('search'))
                                        <x-empty-state icon="bx-search" title="Aucun résultat"
                                            text="Aucun utilisateur ne correspond à « {{ request('search') }} ». Vérifiez l’orthographe ou essayez un autre terme." />
                                    @else
                                        <x-empty-state icon="bx-group" title="Aucun utilisateur" text="Créez le premier compte pour inviter un membre.">
                                            <a href="{{ route('store-user') }}" class="btn btn-primary btn-sm"><i class="bx bx-user-plus"></i>Nouvel utilisateur</a>
                                        </x-empty-state>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :page="$page" :total-pages="$totalPages" :per-page="$perPage" :total="$totalUsers" />
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Inscriptions en attente')

@section('content')
    <x-page-header title="Utilisateurs" subtitle="Demandes d’inscription envoyées depuis le site public. Validez-les pour donner accès à l’espace membre.">
        <a href="{{ route('store-user') }}" class="btn btn-primary"><i class="bx bx-user-plus"></i>Nouvel utilisateur</a>
    </x-page-header>

    <div class="card table-card">
        <div class="card-header">
            @include('pages.users.partials.segment', ['active' => 'pending'])
            <form action="{{ route('users.pending') }}" method="GET" role="search" data-live-search data-target="#pending-list" class="search-field">
                <i class="bx bx-search" aria-hidden="true"></i>
                <label for="search" class="sr-only">Rechercher une demande</label>
                <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom ou e-mail…" autocomplete="off">
                <span class="search-spinner" aria-hidden="true"></span>
            </form>
        </div>

        <div id="pending-list" data-list-region>
            <div class="table-responsive">
                <table class="table table-hover table-stack">
                    <thead>
                        <tr>
                            <th scope="col">Demandeur</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Demande reçue</th>
                            <th scope="col" class="col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="cell-primary">
                                    <div class="user-cell">
                                        <x-avatar :user="$user" />
                                        <div class="min-w-0">
                                            <div class="user-cell-name text-truncate">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <div class="user-cell-meta"><span class="badge badge-warning badge-dot">En attente</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="E-mail">
                                    <div class="text-truncate" style="max-width: 280px;"><a href="mailto:{{ $user->email }}" class="text-body-strong">{{ $user->email }}</a></div>
                                </td>
                                <td data-label="Demande reçue">
                                    <div>
                                        <div class="tabular">{{ $user->created_at?->translatedFormat('j M Y · H\hi') }}</div>
                                        <div class="user-cell-meta">{{ $user->created_at?->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="col-actions">
                                    <div class="row-actions gap-2">
                                        <button type="button" class="btn btn-soft-danger btn-sm"
                                            data-confirm="La demande de {{ $user->first_name }} {{ $user->last_name }} sera définitivement supprimée. La personne pourra refaire une demande."
                                            data-title="Refuser cette inscription ?"
                                            data-action="{{ route('users.reject', $user->id) }}" data-method="DELETE"
                                            data-icon="bx-user-x" data-confirm-label="Refuser">
                                            <i class="bx bx-x"></i><span class="d-none d-lg-inline">Refuser</span>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm"
                                            data-confirm="{{ $user->first_name }} {{ $user->last_name }} pourra se connecter à l’espace membre avec le mot de passe choisi lors de l’inscription, puis compléter son profil."
                                            data-title="Valider cette inscription ?"
                                            data-action="{{ route('users.approve', $user->id) }}" data-method="POST"
                                            data-variant="success" data-icon="bx-user-check" data-confirm-label="Valider">
                                            <i class="bx bx-check"></i>Valider
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="cell-empty">
                                    @if (request('search'))
                                        <x-empty-state icon="bx-search" title="Aucun résultat"
                                            text="Aucune demande ne correspond à « {{ request('search') }} »." />
                                    @else
                                        <x-empty-state icon="bx-user-check" title="Aucune inscription en attente"
                                            text="Les demandes envoyées depuis le site public (« Créer un compte ») apparaîtront ici." />
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

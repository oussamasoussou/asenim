@extends('layouts.app')

@section('title', 'Utilisateurs archivés')

@section('content')
    <x-page-header title="Utilisateurs" subtitle="Les comptes archivés n’ont plus accès à la plateforme. Restaurez-les à tout moment.">
        <a href="{{ route('store-user') }}" class="btn btn-primary"><i class="bx bx-user-plus"></i>Nouvel utilisateur</a>
    </x-page-header>

    <div class="card table-card">
        <div class="card-header">
            @include('pages.users.partials.segment', ['active' => 'archived'])
            <form action="{{ route('users.archives') }}" method="GET" role="search" data-live-search data-target="#archives-list" class="search-field">
                <i class="bx bx-search" aria-hidden="true"></i>
                <label for="search" class="sr-only">Rechercher un utilisateur archivé</label>
                <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom, e-mail ou téléphone…" autocomplete="off">
                <span class="search-spinner" aria-hidden="true"></span>
            </form>
        </div>

        <div id="archives-list" data-list-region>
            <div class="table-responsive">
                <table class="table table-hover table-stack">
                    <thead>
                        <tr>
                            <th scope="col"><x-sort-link column="first_name" label="Utilisateur" default="first_name" /></th>
                            <th scope="col"><x-sort-link column="email" label="Contact" /></th>
                            <th scope="col"><x-sort-link column="deleted_at" label="Archivé le" /></th>
                            <th scope="col" class="col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="cell-primary">
                                    <div class="user-cell">
                                        <x-avatar :user="$user" style="filter: grayscale(1); opacity: .8;" />
                                        <div class="min-w-0">
                                            <div class="user-cell-name text-truncate">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <div class="user-cell-meta">{{ $user->role === 'admin' ? 'Administrateur' : 'Membre' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Contact">
                                    <div>
                                        <div class="text-truncate" style="max-width: 260px;">{{ $user->email }}</div>
                                        <div class="user-cell-meta">{{ $user->phone ?: '—' }}</div>
                                    </div>
                                </td>
                                <td data-label="Archivé le" class="tabular">{{ $user->deleted_at?->translatedFormat('j M Y') }}</td>
                                <td class="col-actions">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-confirm="{{ $user->first_name }} {{ $user->last_name }} retrouvera immédiatement l’accès à la plateforme."
                                        data-title="Restaurer ce compte ?"
                                        data-action="{{ route('users.restore', $user->id) }}" data-method="POST"
                                        data-variant="success" data-icon="bx-undo" data-confirm-label="Restaurer">
                                        <i class="bx bx-undo"></i>Restaurer
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="cell-empty">
                                    @if (request('search'))
                                        <x-empty-state icon="bx-search" title="Aucun résultat"
                                            text="Aucun compte archivé ne correspond à « {{ request('search') }} »." />
                                    @else
                                        <x-empty-state icon="bx-archive" title="Aucun compte archivé"
                                            text="Les utilisateurs archivés apparaîtront ici et pourront être restaurés." />
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

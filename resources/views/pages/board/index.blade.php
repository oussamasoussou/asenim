@extends('layouts.app')

@section('title', 'Bureau exécutif')

@section('content')
    <x-page-header title="Bureau exécutif" subtitle="Membres affichés sur le site public, dans l’ordre de cette liste (accueil et page « Bureau exécutif »).">
        <a href="{{ route('front.bureau') }}" target="_blank" rel="noopener" class="btn btn-secondary"><i class="bx bx-show"></i>Voir sur le site</a>
        <a href="{{ route('board.create') }}" class="btn btn-primary"><i class="bx bx-user-plus"></i>Ajouter un membre</a>
    </x-page-header>

    <div class="card table-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Membres</h2>
                <p class="card-subtitle">Utilisez les flèches pour changer l’ordre d’affichage.</p>
            </div>
            <form action="{{ route('board.index') }}" method="GET" role="search" data-live-search data-target="#board-list" class="search-field">
                <i class="bx bx-search" aria-hidden="true"></i>
                <label for="search" class="sr-only">Rechercher un membre</label>
                <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom, fonction ou établissement…" autocomplete="off">
                <span class="search-spinner" aria-hidden="true"></span>
            </form>
        </div>

        <div id="board-list" data-list-region>
            <div class="table-responsive">
                <table class="table table-hover table-stack">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 1%;">Ordre</th>
                            <th scope="col">Membre</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Sur le site</th>
                            <th scope="col" class="col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($members as $member)
                            <tr>
                                <td data-label="Ordre">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <form method="POST" action="{{ route('board.move', [$member->id, 'up']) }}" data-no-loading>
                                            @csrf
                                            <button type="submit" class="icon-action" title="Monter" aria-label="Monter {{ $member->full_name }}" @disabled($member->id === $firstId)>
                                                <i class="bx bx-chevron-up"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('board.move', [$member->id, 'down']) }}" data-no-loading>
                                            @csrf
                                            <button type="submit" class="icon-action" title="Descendre" aria-label="Descendre {{ $member->full_name }}" @disabled($member->id === $lastId)>
                                                <i class="bx bx-chevron-down"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="cell-primary">
                                    <div class="user-cell">
                                        <span class="ui-avatar"><img src="{{ $member->photoUrl() }}" alt="" loading="lazy"></span>
                                        <div class="min-w-0">
                                            <a href="{{ route('board.edit', $member->id) }}" class="user-cell-name d-block text-truncate">{{ $member->full_name }}</a>
                                            <div class="user-cell-meta">{{ $member->role }}@if ($member->organization) · {{ $member->organization }}@endif</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Contact">
                                    <div>
                                        @if ($member->email)
                                            <a href="mailto:{{ $member->email }}" class="text-body-strong">{{ $member->email }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Sur le site">
                                    @if ($member->is_visible)
                                        <span class="badge badge-success badge-dot">Affiché</span>
                                    @else
                                        <span class="badge badge-neutral badge-dot">Masqué</span>
                                    @endif
                                </td>
                                <td class="col-actions">
                                    <div class="row-actions">
                                        <form method="POST" action="{{ route('board.toggle', $member->id) }}" data-no-loading>
                                            @csrf
                                            <button type="submit" class="icon-action" title="{{ $member->is_visible ? 'Masquer sur le site' : 'Afficher sur le site' }}"
                                                aria-label="{{ $member->is_visible ? 'Masquer' : 'Afficher' }} {{ $member->full_name }} sur le site">
                                                <i class="bx {{ $member->is_visible ? 'bx-hide' : 'bx-show' }}"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('board.edit', $member->id) }}" class="icon-action is-primary" title="Modifier" aria-label="Modifier {{ $member->full_name }}">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <button type="button" class="icon-action is-danger" title="Retirer" aria-label="Retirer {{ $member->full_name }}"
                                            data-confirm="{{ $member->full_name }} ({{ $member->role }}) sera retiré(e) du Bureau exécutif et n’apparaîtra plus sur le site. Sa photo sera supprimée."
                                            data-title="Retirer ce membre ?"
                                            data-action="{{ route('board.destroy', $member->id) }}" data-method="DELETE"
                                            data-icon="bx-trash" data-confirm-label="Retirer">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="cell-empty">
                                    @if (request('search'))
                                        <x-empty-state icon="bx-search" title="Aucun résultat" text="Aucun membre ne correspond à « {{ request('search') }} »." />
                                    @else
                                        <x-empty-state icon="bx-id-card" title="Aucun membre" text="Ajoutez les membres du Bureau exécutif pour les afficher sur le site public.">
                                            <a href="{{ route('board.create') }}" class="btn btn-primary btn-sm"><i class="bx bx-user-plus"></i>Ajouter un membre</a>
                                        </x-empty-state>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :page="$page" :total-pages="$totalPages" :per-page="$perPage" :total="$total" />
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Liste des actualités et événements')

@section('content')
<div>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mb-2 mb-sm-0">
            <h5 class="mb-0 me-3">Liste des actualités (Archivés)</h5>
            <a href="#" class="text-decoration-none d-flex align-items-center gap-2">
                <span class="tf-icons bx bx-archive-in"></span>
                <a href="{{ route('news.index') }}">
                    <h6 class="mb-0" style="color:#696cff">Désarchivés</h6>
                </a>
            </a>
        </div>
    </div>
</div>

<!-- Table des actualités -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">
            <div class="input-group input-group-merge">
                <span class="input-group-text" id="basic-addon-search31">
                    <i class="bx bx-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher..."
                    value="{{ request('search') }}" />
            </div>
        </h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de création</th>
                    <th>Créé par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
                        <td>
                            <!-- Bouton de restauration -->
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#restoreModalNews" data-news-id="{{ $item->id }}">
                                <i class="bx bx-undo me-1"></i>
                            </button>

                            <!-- Modale de confirmation de restauration -->
                            <div class="modal fade" id="restoreModalNews" tabindex="-1" aria-labelledby="restoreModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="restoreModalLabel">Confirmation de restauration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir restaurer cette actualité ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <form id="restoreForm" method="POST" action="" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Restaurer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mb-2 mb-sm-0">
            <h5 class="mb-0 me-3">Liste des événements (Archivés)</h5>
        </div>
    </div>
</div>
<!-- Table des événements -->
<div class="card mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">
            <div class="input-group input-group-merge">
                <span class="input-group-text" id="basic-addon-search32">
                    <i class="bx bx-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher..."
                    value="{{ request('search') }}" />
            </div>
        </h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date d'événement</th>
                    <th>Créé par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
                        <td>
                            <!-- Bouton de restauration -->
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#restoreModalNews-{{ $item->id }}">
                                <i class="bx bx-undo me-1"></i>
                            </button>

                            <!-- Modale de confirmation de restauration -->
                            <div class="modal fade" id="restoreModalNews-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="restoreModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="restoreModalLabel">Confirmation de restauration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir restaurer cette actualité ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <form id="restoreForm-{{ $item->id }}" method="POST"
                                                action="{{ route('news.restore', $item->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Restaurer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
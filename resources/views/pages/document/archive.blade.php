@extends('layouts.app')

@section('title', 'Liste des documents')

@section('content')
<div>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mb-2 mb-sm-0">
            <h5 class="mb-0 me-3">Liste des documents (Archivés)</h5>
            <a href="#" class="text-decoration-none d-flex align-items-center gap-2">
                <span class="tf-icons bx bx-archive-in"></span>
                <a href="{{ route('documents.non_archived') }}">
                    <h6 class="mb-0" style="color:#696cff">Désarchivés</h6>
                </a>
            </a>
        </div>
        <a href="{{ route('store-document') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-file-plus"></span>&nbsp; Ajouter un document
        </a>
    </div>
</div>

<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">
            <div class="input-group input-group-merge">
                <span class="input-group-text" id="basic-addon-search31">
                    <i class="bx bx-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher..."
                    aria-label="Rechercher..." aria-describedby="basic-addon-search31"
                    value="{{ request('search') }}" />
            </div>
        </h5>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('documents.non_archived', ['sort' => 'file_name', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Nom du fichier
                            <i
                                class="bx bx-sort @if (request('sort') == 'file_name' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'file_name' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>
                    <th>Fichier</th>
                    <th>
                        <a href="{{ route('documents.non_archived', ['sort' => 'member_type', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Visibilité
                            <i
                                class="bx bx-sort @if (request('sort') == 'member_type' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'member_type' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('documents.non_archived', ['sort' => 'created_at', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                            style="color:#566a7f">
                            Date de création
                            <i
                                class="bx bx-sort @if (request('sort') == 'created_at' && request('order') == 'asc') bx-sort-alt @elseif (request('sort') == 'created_at' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                        </a>
                    </th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Boucle à travers les documents -->
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->file_name }}</td>
                        <td>
                            <!-- Lien pour télécharger le fichier -->
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                class="btn btn-outline-info">
                                <i class="bx bx-download"></i> Télécharger
                            </a>
                        </td>
                        <td>
                            @if ($document->member_type == 'permanent')
                                <span class="text-dark">Membre permanant</span>
                            @elseif ($document->member_type == 'non_permanent')
                                <span class="text-success">>Membre non permanent</span>
                            @elseif ($document->member_type == 'all_members')
                                <span class="text-primary">Tous les membres</span>
                            @else
                                <span>{{ $document->member_type }}</span>
                            @endif
                        </td>

                        <td>{{ $document->created_at->format('d/m/Y') }}</td>
                        <td>
                       

                            <!-- Bouton de suppression -->
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#restoreModal" data-document-id="{{ $document->id }}">
                                <i class="bx bx-undo me-1"></i>
                            </button>

                            <!-- Modale de confirmation de suppression -->
                            <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="restoreModalLabel">Confirmation de restauration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir restaurer ce document ?
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

    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <form action="{{ route('documents.non_archived') }}" method="get" class="d-flex justify-content-start">
                    <div class="form-group position-relative">
                        <select name="perPage" id="perPage" class="form-control custom-select"
                            onchange="this.form.submit()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        </select>
                        <i class="fas fa-chevron-down position-absolute"
                            style="top: 50%; right: 10px; transform: translateY(-50%); color: #adafb2; font-size: 0.9rem;"></i>
                    </div>
                </form>
            </div>

            <div class="col-6 text-end">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('documents.non_archived', ['page' => 1, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevrons-left"></i>
                            </a>
                        </li>
                        <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('documents.non_archived', ['page' => $page - 1, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevron-left"></i>
                            </a>
                        </li>
                        @for ($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $i == $page ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ route('documents.non_archived', ['page' => $i, 'perPage' => $perPage]) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('documents.non_archived', ['page' => $page + 1, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevron-right"></i>
                            </a>
                        </li>
                        <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('documents.non_archived', ['page' => $totalPages, 'perPage' => $perPage]) }}">
                                <i class="tf-icon bx bx-chevrons-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
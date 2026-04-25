@extends('layouts.app')

@section('title', 'Documents Archivés')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Documents Archivés</h4>
            <p class="text-muted mb-0">Ressources en fin de cycle de vie ou désactivées.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <a href="{{ route('documents.non_archived') }}" class="btn btn-outline-primary rounded-pill d-flex align-items-center shadow-sm hover-lift px-4">
                <i class="bx bx-list-ul me-2"></i> Voir les Actifs
            </a>
            <a href="{{ route('store-document') }}" class="btn btn-primary rounded-pill d-flex align-items-center shadow hover-lift px-4" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                <i class="bx bx-file-plus me-2"></i> Nouveau
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden" style="background-color: #fcfcfc;">
        <!-- Search bar -->
        <div class="card-header bg-transparent border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-muted fw-semibold"><i class="bx bx-archive text-secondary me-2"></i>Liste des archives</h6>
            <div class="input-container w-auto bg-white border">
                <i class="bx bx-search text-muted fs-5 ps-2"></i>
                <input type="text" id="searchInput" placeholder="Rechercher..." value="{{ request('search') }}" class="w-100" />
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-75">
                    <tr>
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('documents.non_archived', ['sort' => 'file_name', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-muted fw-semibold d-flex align-items-center gap-1">
                                Fichier
                                <i class="bx bx-sort @if(request('sort') == 'file_name' && request('order') == 'asc') bx-sort-alt @elseif(request('sort') == 'file_name' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                            </a>
                        </th>
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('documents.non_archived', ['sort' => 'member_type', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-muted fw-semibold d-flex align-items-center gap-1">
                                Accessibilité
                                <i class="bx bx-sort @if(request('sort') == 'member_type' && request('order') == 'asc') bx-sort-alt @elseif(request('sort') == 'member_type' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                            </a>
                        </th>
                        <th class="border-0 px-4 py-3">
                            <a href="{{ route('documents.non_archived', ['sort' => 'created_at', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-muted fw-semibold d-flex align-items-center gap-1">
                                Date d'ajout
                                <i class="bx bx-sort @if(request('sort') == 'created_at' && request('order') == 'asc') bx-sort-alt @elseif(request('sort') == 'created_at' && request('order') == 'desc') bx-sort-alt-up @endif"></i>
                            </a>
                        </th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 opacity-75">
                    @forelse($documents as $document)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3 text-secondary bg-light rounded-circle d-flex align-items-center justify-content-center border">
                                        <i class="bx bxs-file-pdf fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-secondary">{{ $document->file_name }}</h6>
                                        <small class="text-muted"><i class="bx bx-archive-in me-1"></i>Archivé</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-label-secondary rounded-pill px-3 py-2 fw-semibold">
                                    {{ $document->member_type == 'all_members' ? 'Tous les membres' : ($document->member_type == 'permanent' ? 'GDI' : 'Non permanent') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-muted">
                                {{ $document->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-secondary" title="Aperçu / Télécharger">
                                        <i class="bx bx-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-light border rounded-circle shadow-none text-success" data-bs-toggle="modal" data-bs-target="#restoreModalDocument" data-document-id="{{ $document->id }}" title="Restaurer le document">
                                        <i class="bx bx-undo"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bx bx-folder text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                                <h6 class="text-muted">Aucun document archivé</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="card-footer bg-transparent border-top border-light py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center text-muted text-sm">
                <span>Afficher</span>
                <form action="{{ route('documents.non_archived') }}" method="get" class="mx-2">
                    <select name="perPage" class="form-select form-select-sm shadow-none border bg-white rounded-pill px-3" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </form>
                <span>par page</span>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm justify-content-end mb-0 gap-1">
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('documents.non_archived', ['page' => 1, 'perPage' => $perPage]) }}"><i class="bx bx-chevrons-left"></i></a>
                    </li>
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('documents.non_archived', ['page' => $page - 1, 'perPage' => $perPage]) }}"><i class="bx bx-chevron-left"></i></a>
                    </li>
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link rounded-circle {{ $i == $page ? 'bg-secondary border-secondary text-white shadow-sm' : 'text-secondary' }}" href="{{ route('documents.non_archived', ['page' => $i, 'perPage' => $perPage]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('documents.non_archived', ['page' => $page + 1, 'perPage' => $perPage]) }}"><i class="bx bx-chevron-right"></i></a>
                    </li>
                    <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('documents.non_archived', ['page' => $totalPages, 'perPage' => $perPage]) }}"><i class="bx bx-chevrons-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Restore Modal -->
<div class="modal fade" id="restoreModalDocument" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 px-5">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-label-success" style="width: 80px; height: 80px;">
                    <i class="bx bx-refresh text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold mb-2">Restaurer</h4>
                <p class="text-muted">Êtes-vous sûr de vouloir restaurer ce document ? Il sera de nouveau disponible pour les membres concernés.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                <form id="restoreFormDocument" method="POST" action="" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Oui, restaurer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var restoreModal = document.getElementById('restoreModalDocument');
        if(restoreModal) {
            restoreModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var docId = button.getAttribute('data-document-id');
                var form = document.getElementById('restoreFormDocument');
                // Adaptation de l'action selon la route
                form.action = '/documents/restore/' + docId; // Modifier si la route diffère
            });
        }
    });
</script>
@endsection
@extends('layouts.app')

@section('title', 'Liste des documents')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Ressources & Documents</h4>
            <p class="text-muted mb-0">Recherchez et téléchargez les documents de la plateforme.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <a href="{{ route('documents.archived') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center shadow-sm hover-lift px-4">
                <i class="bx bx-archive-in me-2"></i> Voir les Archivés
            </a>
            <a href="{{ route('store-document') }}" class="btn btn-primary rounded-pill d-flex align-items-center shadow hover-lift px-4" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                <i class="bx bx-file-plus me-2"></i> Nouveau Document
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden">
        <!-- Search Bar -->
        <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-muted fw-semibold">Documents disponibles</h6>
            <form action="{{ route('documents.non_archived') }}" method="GET" class="input-container w-auto m-0" id="searchForm">
                <i class="bx bx-search text-muted fs-5 ps-2"></i>
                <input type="hidden" name="perPage" value="{{ $perPage }}">
                <input type="text" name="search" id="searchInput" placeholder="Rechercher un document..." value="{{ request('search') }}" class="w-100 border-0 bg-transparent outline-0 shadow-none" style="outline: none;" />
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
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
                        <th class="border-0 px-4 py-3 text-muted fw-semibold">Ajouté par</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0" id="document-table-body">
                    @forelse($documents as $document)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3 text-primary bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-file-pdf fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $document->file_name }}</h6>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">
                                            Document
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($document->member_type == 'permanent')
                                    <span class="badge bg-label-info rounded-pill px-3 py-2 fw-semibold">GDI</span>
                                @elseif ($document->member_type == 'non_permanent')
                                    <span class="badge bg-label-success rounded-pill px-3 py-2 fw-semibold">Non permanent</span>
                                @elseif ($document->member_type == 'all_members')
                                    <span class="badge bg-label-primary rounded-pill px-3 py-2 fw-semibold">Tous les membres</span>
                                @else
                                    <span class="badge bg-label-secondary rounded-pill px-3 py-2 fw-semibold">{{ ucfirst($document->member_type) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-muted">
                                <i class="bx bx-calendar-event me-1 text-muted"></i>
                                {{ collect(explode(' ', $document->created_at->format('d/m/Y')))->first() }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <div class="rounded-circle bg-label-secondary d-flex align-items-center justify-content-center text-secondary fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ $document->user ? strtoupper(substr($document->user->first_name, 0, 1)) : '?' }}
                                        </div>
                                    </div>
                                    <span class="text-muted small fw-medium">
                                        {{ $document->user ? $document->user->first_name . ' ' . $document->user->last_name : 'Inconnu' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm d-flex align-items-center gap-1 hover-lift">
                                        <i class="bx bx-download"></i> <span class="d-none d-md-inline">Télécharger</span>
                                    </a>
                                    
                                    <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-info" title="Modifier">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalDocument" data-document-id="{{ $document->id }}" title="Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bx bx-folder-open text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                                <h6 class="text-muted fw-medium mb-1">Aucun document trouvé</h6>
                                <p class="text-muted small">Essayez de modifier votre recherche ou ajoutez un nouveau document.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="card-footer bg-white border-top border-light py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center text-muted text-sm">
                <span>Afficher</span>
                <form action="{{ route('documents.non_archived') }}" method="get" class="mx-2">
                    <select name="perPage" id="perPageSelect" class="form-select form-select-sm shadow-none border-light rounded-pill px-3">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </form>
                <span>par page</span>
            </div>

            <nav aria-label="Page navigation" id="pagination-container">
                <ul class="pagination pagination-sm justify-content-end mb-0 gap-1">
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('documents.non_archived', ['page' => 1, 'perPage' => $perPage]) }}"><i class="bx bx-chevrons-left"></i></a>
                    </li>
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <a class="page-link rounded-circle" href="{{ route('documents.non_archived', ['page' => $page - 1, 'perPage' => $perPage]) }}"><i class="bx bx-chevron-left"></i></a>
                    </li>
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link rounded-circle {{ $i == $page ? 'bg-primary border-primary text-white shadow-sm' : '' }}" href="{{ route('documents.non_archived', ['page' => $i, 'perPage' => $perPage]) }}">{{ $i }}</a>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModalDocument" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 px-5">
                <i class="bx bx-error-circle text-danger mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mb-2">Confirmation</h4>
                <p class="text-muted">Êtes-vous sûr de vouloir supprimer ce document ?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" action="" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Oui, supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModalDocument = document.getElementById('deleteModalDocument');
        deleteModalDocument.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var documentId = button.getAttribute('data-document-id');
            var form = deleteModalDocument.querySelector('#deleteForm');
            // Mettre à jour l'action du formulaire
            form.action = '/documents/' + documentId;
        })
    });
</script>

<script>
$(document).ready(function() {
    let timeout = null;
    let currentRequest = null;

    function fetchDocuments(url = null, isInitialSearch = false) {
        const search = $('#searchInput').val();
        const perPage = $('#perPageSelect').val() || 10;
        
        let baseUrl = url || "{{ route('documents.non_archived') }}";
        
        let [path, queryString] = baseUrl.split('?');
        let params = new URLSearchParams(queryString || "");
        
        params.set('search', search);
        params.set('perPage', perPage);
        
        const finalUrl = path + '?' + params.toString();

        if (currentRequest) {
            currentRequest.abort();
        }

        // On ne met plus d'opacité 0.5 pour garder la fluidité
        currentRequest = $.ajax({
            url: finalUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(response) {
                updateTable(response.documents, response.isAdmin);
                updatePagination(response);
                currentRequest = null;
            },
            error: function(xhr, status, error) {
                if (status !== 'abort') {
                    console.error("Erreur AJAX:", error);
                }
            }
        });
    }

    function updateTable(documents, isAdmin) {
        const tbody = $('#document-table-body');
        tbody.empty();

        if (documents.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="mb-3">
                            <i class="bx bx-folder-open text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                        <h6 class="text-muted fw-medium mb-1">Aucun document trouvé</h6>
                        <p class="text-muted small">Essayez de modifier votre recherche.</p>
                    </td>
                </tr>
            `);
            return;
        }

        documents.forEach(doc => {
            const dateStr = new Date(doc.created_at).toLocaleDateString('fr-FR');
            const author = doc.user ? `${doc.user.first_name} ${doc.user.last_name}` : 'Inconnu';
            const initials = doc.user ? doc.user.first_name.charAt(0).toUpperCase() : '?';
            const badge = getBadge(doc.member_type);

            let actionsHtml = `
                <div class="d-flex justify-content-end gap-2">
                    <a href="/storage/${doc.file_path}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm d-flex align-items-center gap-1">
                        <i class="bx bx-download"></i>
                    </a>
            `;

            if (isAdmin) {
                actionsHtml += `
                    <a href="/documents/${doc.id}/edit" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-info">
                        <i class="bx bx-edit-alt"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalDocument" data-document-id="${doc.id}">
                        <i class="bx bx-trash"></i>
                    </button>
                `;
            }
            actionsHtml += `</div>`;

            tbody.append(`
                <tr>
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-3 text-primary bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bxs-file-pdf fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">${doc.file_name}</h6>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">${badge}</td>
                    <td class="px-4 py-3 text-muted">${dateStr}</td>
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs me-2">
                                <div class="rounded-circle bg-label-secondary d-flex align-items-center justify-content-center text-secondary fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    ${initials}
                                </div>
                            </div>
                            <span class="text-muted small fw-medium">${author}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-end">${actionsHtml}</td>
                </tr>
            `);
        });
    }

    function getBadge(type) {
        if (type === 'permanent') return '<span class="badge bg-label-info rounded-pill px-3 py-2 fw-semibold">GDI</span>';
        if (type === 'non_permanent') return '<span class="badge bg-label-success rounded-pill px-3 py-2 fw-semibold">Non permanent</span>';
        if (type === 'all_members') return '<span class="badge bg-label-primary rounded-pill px-3 py-2 fw-semibold">Tous les membres</span>';
        return `<span class="badge bg-label-secondary rounded-pill px-3 py-2 fw-semibold text-capitalize">${type}</span>`;
    }

    function updatePagination(data) {
        const ul = $('#pagination-container ul');
        if (!ul.length) return;
        ul.empty();

        ul.append(`
            <li class="page-item ${data.page == 1 ? 'disabled' : ''}">
                <a class="page-link rounded-circle ajax-page" href="#" data-page="${data.page - 1}"><i class="bx bx-chevron-left"></i></a>
            </li>
        `);

        for (let i = 1; i <= data.totalPages; i++) {
            const activeClass = i == data.page ? 'active' : '';
            const linkClass = i == data.page ? 'bg-primary border-primary text-white shadow-sm' : '';
            ul.append(`
                <li class="page-item ${activeClass}">
                    <a class="page-link rounded-circle ajax-page ${linkClass}" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        ul.append(`
            <li class="page-item ${data.page == data.totalPages ? 'disabled' : ''}">
                <a class="page-link rounded-circle ajax-page" href="#" data-page="${data.page + 1}"><i class="bx bx-chevron-right"></i></a>
            </li>
        `);
    }

    // --- Event Listeners ---

    $('#searchInput').on('input', function() {
        const query = $(this).val().toLowerCase();
        
        // 1. Filtrage local immédiat (Sensation de "dynamique")
        const rows = $('#document-table-body tr');
        let visibleCount = 0;
        
        rows.each(function() {
            const fileName = $(this).find('h6').text().toLowerCase();
            const author = $(this).find('span.text-muted.small').text().toLowerCase();
            
            if (fileName.includes(query) || author.includes(query)) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        // 2. Synchronisation AJAX en arrière-plan (Pour la pagination)
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            fetchDocuments();
        }, 300);
    });

    $(document).on('click', '.ajax-page', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && page > 0) {
            const baseUrl = "{{ route('documents.non_archived') }}";
            fetchDocuments(`${baseUrl}?page=${page}`);
        }
    });

    $('#perPageSelect').on('change', function() {
        fetchDocuments();
    });

    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        fetchDocuments();
    });
});
</script>
@endsection
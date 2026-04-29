@extends('layouts.app')

@section('title', 'Actualités & Événements Archivés')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Actualités & Événements Archivés</h4>
            <p class="text-muted mb-0">Contenu obsolète ou retiré de la publication.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary rounded-pill d-flex align-items-center shadow-sm hover-lift px-4">
                <i class="bx bx-list-ul me-2"></i> Voir les contenus actifs
            </a>
            <a href="{{ route('store-news') }}" class="btn btn-primary rounded-pill d-flex align-items-center shadow hover-lift px-4" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                <i class="bx bx-news me-2"></i> Publier
            </a>
        </div>
    </div>

    <!-- Section Actualités -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden mb-5" style="background-color: #fcfcfc;">
        <div class="card-header bg-transparent border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-secondary d-flex align-items-center"><i class="bx bx-news fs-4 me-2"></i> Actualités archivées</h6>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-75">
                    <tr>
                        <th class="border-0 px-4 py-3">Titre de la publication</th>
                        <th class="border-0 px-4 py-3">Date d'archivage</th>
                        <th class="border-0 px-4 py-3">Auteur</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 opacity-75">
                    @forelse($news as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3 bg-secondary rounded shadow-sm d-flex align-items-center justify-content-center border text-white" style="width: 45px; height: 45px;">
                                        <i class="bx bx-archive fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-secondary">{{ $item->title }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted">
                                <i class="bx bx-calendar me-1"></i> {{ $item->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-muted fw-medium">{{ $item->user->first_name }} {{ $item->user->last_name }}</span>
                            </td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-primary" 
                                        title="Afficher les détails"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailsModal"
                                        data-title="{{ $item->title }}"
                                        data-content="{{ $item->content }}"
                                        data-date="{{ $item->created_at->format('d/m/Y') }}"
                                        data-author="{{ $item->user->first_name }} {{ $item->user->last_name }}"
                                        data-type="Actualité"
                                        data-image="{{ $item->image ? asset('storage/' . $item->image) : '' }}">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light border rounded-circle shadow-none text-success" data-bs-toggle="modal" data-bs-target="#restoreModalNews{{ $item->id }}" title="Restaurer l'actualité">
                                        <i class="bx bx-undo"></i>
                                    </button>
                                </div>

                                <!-- Restore Modal -->
                                <div class="modal fade" id="restoreModalNews{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                            <div class="modal-header border-0 pb-0 justify-content-end">
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center pt-0 px-5">
                                                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-label-success" style="width: 80px; height: 80px;">
                                                    <i class="bx bx-refresh text-success" style="font-size: 3rem;"></i>
                                                </div>
                                                <h4 class="fw-bold mb-2">Restaurer</h4>
                                                <p class="text-muted">Voulez-vous vraiment restaurer "<strong>{{ $item->title }}</strong>" ?</p>
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center pb-4">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                                                <form method="POST" action="" class="d-inline"> {{-- Action dynamically updated or static --}}
                                                    @csrf
                                                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Oui, restaurer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Aucune actualité archivée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Événements -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden" style="background-color: #fcfcfc;">
        <div class="card-header bg-transparent border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-secondary d-flex align-items-center"><i class="bx bx-calendar fs-4 me-2"></i> Événements archivés</h6>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-75">
                    <tr>
                        <th class="border-0 px-4 py-3">Titre de l'événement</th>
                        <th class="border-0 px-4 py-3">Date passée</th>
                        <th class="border-0 px-4 py-3">Auteur</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 opacity-75">
                    @forelse($events as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3 bg-secondary rounded shadow-sm d-flex align-items-center justify-content-center text-white" style="width: 45px; height: 45px;">
                                        <i class="bx bx-archive fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-secondary">{{ $item->title }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="fw-medium text-muted">{{ date('d/m/Y - H:i', strtotime($item->date)) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-muted fw-medium">{{ $item->user->first_name }} {{ $item->user->last_name }}</span>
                            </td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-primary" 
                                        title="Afficher les détails"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailsModal"
                                        data-title="{{ $item->title }}"
                                        data-content="{{ $item->content }}"
                                        data-date="{{ date('d/m/Y - H:i', strtotime($item->date)) }}"
                                        data-author="{{ $item->user->first_name }} {{ $item->user->last_name }}"
                                        data-type="Événement"
                                        data-image="{{ $item->image ? asset('storage/' . $item->image) : '' }}">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light border rounded-circle shadow-none text-success" data-bs-toggle="modal" data-bs-target="#restoreModalEvent{{ $item->id }}" title="Restaurer l'événement">
                                        <i class="bx bx-undo"></i>
                                    </button>
                                </div>
                                
                                <!-- Restore Modal Event -->
                                <div class="modal fade" id="restoreModalEvent{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                            <div class="modal-header border-0 pb-0 justify-content-end">
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center pt-0 px-5">
                                                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-label-success" style="width: 80px; height: 80px;">
                                                    <i class="bx bx-refresh text-success" style="font-size: 3rem;"></i>
                                                </div>
                                                <h4 class="fw-bold mb-2">Restaurer</h4>
                                                <p class="text-muted">Voulez-vous restaurer l'événement "<strong>{{ $item->title }}</strong>" ?</p>
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center pb-4">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                                                <form method="POST" action="{{ route('news.restore', $item->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Oui, restaurer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Aucun événement archivé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom border-light px-4 py-3">
                <div class="d-flex align-items-center">
                    <div id="modalTypeBadge" class="badge rounded-pill me-3"></div>
                    <h5 class="modal-title fw-bold text-dark" id="modalTitle"></h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-5 mb-4 mb-md-0" id="modalImageContainer">
                        <img id="modalImage" src="" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" style="max-height: 300px;" alt="Publication image">
                    </div>
                    <div class="col-md-7" id="modalInfoContainer">
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bx bx-calendar me-1 fs-5"></i>
                                <span id="modalDate"></span>
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bx bx-user me-1 fs-5"></i>
                                <span id="modalAuthor"></span>
                            </div>
                        </div>
                        <div class="publication-content text-secondary" id="modalContent" style="white-space: pre-wrap; line-height: 1.6;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailsModal = document.getElementById('detailsModal');
    if (detailsModal) {
        detailsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            const title = button.getAttribute('data-title');
            const content = button.getAttribute('data-content');
            const date = button.getAttribute('data-date');
            const author = button.getAttribute('data-author');
            const type = button.getAttribute('data-type');
            const image = button.getAttribute('data-image');

            const modalTitle = detailsModal.querySelector('#modalTitle');
            const modalTypeBadge = detailsModal.querySelector('#modalTypeBadge');
            const modalDate = detailsModal.querySelector('#modalDate');
            const modalAuthor = detailsModal.querySelector('#modalAuthor');
            const modalContent = detailsModal.querySelector('#modalContent');
            const modalImage = detailsModal.querySelector('#modalImage');
            const modalImageContainer = detailsModal.querySelector('#modalImageContainer');
            const modalInfoContainer = detailsModal.querySelector('#modalInfoContainer');

            modalTitle.textContent = title;
            modalDate.textContent = date;
            modalAuthor.textContent = author;
            modalContent.textContent = content;
            modalTypeBadge.textContent = type;
            
            if (type === 'Événement') {
                modalTypeBadge.className = 'badge bg-label-success rounded-pill me-3';
            } else {
                modalTypeBadge.className = 'badge bg-label-primary rounded-pill me-3';
            }

            if (image) {
                modalImage.src = image;
                modalImageContainer.style.display = 'block';
                modalInfoContainer.className = 'col-md-7';
            } else {
                modalImageContainer.style.display = 'none';
                modalInfoContainer.className = 'col-md-12';
            }
        });
    }
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Actualités et Événements')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Actualités & Événements</h4>
            <p class="text-muted mb-0">Tenez votre équipe informée des dernières nouveautés.</p>
        </div>
        <div class="d-flex gap-3 mt-3 mt-md-0">
            <a href="{{ route('news.archived') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center shadow-sm hover-lift px-4">
                <i class="bx bx-archive-in me-2"></i> Voir les Archivés
            </a>
            <a href="{{ route('store-news') }}" class="btn btn-primary rounded-pill d-flex align-items-center shadow hover-lift px-4" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                <i class="bx bx-news me-2"></i> Publier
            </a>
        </div>
    </div>

    <!-- Section Actualités -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"><i class="bx bx-news fs-4 me-2"></i> Actualités récentes</h6>
            <div class="input-container w-auto">
                <i class="bx bx-search text-muted fs-5 ps-2"></i>
                <input type="text" id="searchInputNews" placeholder="Rechercher une actualité..." value="{{ request('search') }}" class="w-100" />
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="border-0 px-4 py-3">Titre de la publication</th>
                        <th class="border-0 px-4 py-3">Date de création</th>
                        <th class="border-0 px-4 py-3">Auteur</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($news as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3 bg-light rounded shadow-sm d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded w-100 h-100 object-fit-cover" alt="Image">
                                        @else
                                            <i class="bx bx-image-alt text-muted fs-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $item->title }}</h6>
                                        <span class="badge bg-label-primary rounded-pill mt-1" style="font-size: 0.70rem;">Actualité</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted">
                                <i class="bx bx-calendar-event me-1"></i> {{ $item->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-dark fw-medium">{{ $item->user->first_name }} {{ $item->user->last_name }}</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-info" title="Modifier">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalNews{{ $item->id }}" title="Archiver / Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal News -->
                                <div class="modal fade" id="deleteModalNews{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                            <div class="modal-header border-0 pb-0 justify-content-end">
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center pt-0 px-5">
                                                <i class="bx bx-error-circle text-danger mb-3" style="font-size: 4rem;"></i>
                                                <h4 class="fw-bold mb-2">Confirmation</h4>
                                                <p class="text-muted">Voulez-vous vraiment supprimer "<strong>{{ $item->title }}</strong>" ?</p>
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center pb-4">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                                                <form method="POST" action="{{ route('news.delete', $item->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Oui, supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Aucuune actualité à afficher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Événements -->
    <div class="card border-0 shadow-md rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-success d-flex align-items-center"><i class="bx bx-calendar-star fs-4 me-2"></i> Événements à venir</h6>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50">
                    <tr>
                        <th class="border-0 px-4 py-3">Titre de l'événement</th>
                        <th class="border-0 px-4 py-3">Date prévue</th>
                        <th class="border-0 px-4 py-3">Auteur</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($events as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3 bg-label-success rounded shadow-sm d-flex align-items-center justify-content-center text-success" style="width: 45px; height: 45px;">
                                        <i class="bx bx-calendar fs-3"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $item->title }}</h6>
                                        <span class="badge bg-label-success rounded-pill mt-1" style="font-size: 0.70rem;">Événement</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="fw-bold text-dark"><i class="bx bx-time-five text-muted me-1"></i> {{ date('d/m/Y - H:i', strtotime($item->date)) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-dark fw-medium">{{ $item->user->first_name }} {{ $item->user->last_name }}</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-info" title="Modifier">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-circle shadow-none text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalEvent{{ $item->id }}" title="Archiver / Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal Event -->
                                <div class="modal fade" id="deleteModalEvent{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                            <div class="modal-header border-0 pb-0 justify-content-end">
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center pt-0 px-5">
                                                <i class="bx bx-error-circle text-danger mb-3" style="font-size: 4rem;"></i>
                                                <h4 class="fw-bold mb-2">Confirmation</h4>
                                                <p class="text-muted">Voulez-vous vraiment supprimer l'événement "<strong>{{ $item->title }}</strong>" ?</p>
                                            </div>
                                            <div class="modal-footer border-0 justify-content-center pb-4">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                                                <form method="POST" action="{{ route('news.delete', $item->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Oui, supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Aucun événement à venir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
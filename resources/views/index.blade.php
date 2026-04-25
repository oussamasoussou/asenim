@extends('layouts.app')

@section('title', 'Tableau de bord - Actualités et Documents')

@section('content')
<div class="container-fluid py-4">

    <!-- Section header for News -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h4 class="mb-0">Actualités Récentes</h4>
        <span class="badge-premium">{{ count($news) }} Articles</span>
    </div>
    
    <!-- Section Actualités -->
    <div class="grid-container mb-5">
        @forelse($news as $item)
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <!-- Red title if date is past, otherwise normal -->
                        <h5 class="card-title text-truncate-2 {{ $item->date < now() ? 'text-red' : '' }} m-0" title="{{ $item->title }}">
                            {{ $item->title }}
                        </h5>
                    </div>
                    
                    <div class="mb-3 d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                        <i class="bx bx-calendar me-2 text-primary" style="font-size: 1.1rem;"></i>
                        <span>Événement: {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</span>
                    </div>

                    <div class="d-flex align-items-center pt-3 mt-auto border-top border-light">
                        <div class="avatar avatar-sm me-3">
                            <div class="rounded-circle bg-label-primary d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px;">
                                {{ strtoupper(substr($item->user->first_name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <p class="card-text mb-0" style="font-size: 0.85rem; font-weight: 500;">
                                {{ $item->user->first_name ?? 'Utilisateur' }} {{ $item->user->last_name ?? '' }}
                            </p>
                            <p class="card-text mb-0 text-muted" style="font-size: 0.75rem;">
                                Publié {{ $item->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="#">Voir l'article</a>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-label-secondary rounded-circle mb-3" style="width: 80px; height: 80px;">
                    <i class="bx bx-news text-muted" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="text-muted">Aucune actualité disponible</h5>
                <p class="text-muted mb-0">Revenez plus tard pour de nouvelles informations.</p>
            </div>
        @endforelse
    </div>


    <hr class="my-5 border-light">

    <!-- Section header for Documents -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Derniers Documents</h4>
        <a href="{{ route('documents.non_archived') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Tout voir</a>
    </div>

    <!-- Section Documents -->
    <div class="row g-4 mb-5">
        @forelse($documents as $document)
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="card h-100 text-center document-card">
                    <div class="card-body d-flex flex-column align-items-center pt-4">
                        
                        <!-- Icône ou aperçu du fichier cliquable -->
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="w-100 mb-4 text-decoration-none">
                            @if(in_array(strtolower($document->type), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <div class="rounded-3 overflow-hidden shadow-sm mx-auto mb-3" style="width: 100%; height: 120px;">
                                    <img src="{{ asset('storage/' . $document->file_path) }}" class="w-100 h-100 object-fit-cover" alt="Aperçu">
                                </div>
                            @else
                                <div class="doc-icon-wrapper shadow-sm">
                                    @if(in_array(strtolower($document->type), ['pdf', 'application/pdf']))
                                        <i class="bx bxs-file-pdf text-danger" style="font-size: 3rem;"></i>
                                    @elseif(in_array(strtolower($document->type), ['doc', 'docx', 'word']))
                                        <i class="bx bxs-file-doc text-primary" style="font-size: 3rem;"></i>
                                    @elseif(in_array(strtolower($document->type), ['xls', 'xlsx', 'excel', 'csv']))
                                        <i class="bx bxs-spreadsheet text-success" style="font-size: 3rem;"></i>
                                    @else
                                        <i class="bx bxs-file-blank text-secondary" style="font-size: 3rem;"></i>
                                    @endif
                                </div>
                            @endif
                            <h5 class="card-title text-truncate w-100 px-2 mt-2" title="{{ $document->file_name }}">
                                {{ $document->file_name }}
                            </h5>
                        </a>

                        <div class="mt-auto w-100 text-start bg-label-secondary p-3 rounded-3" style="font-size: 0.8rem;">
                            <div class="d-flex mb-1">
                                <span class="text-muted fw-semibold me-2">Date:</span>
                                <span class="text-dark">{{ $document->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex mb-1">
                                <span class="text-muted fw-semibold me-2">Auteur:</span>
                                <span class="text-dark text-truncate">{{ $document->user->first_name ?? 'Inconnu' }} {{ $document->user->last_name ?? '' }}</span>
                            </div>
                            <div class="d-flex text-muted mt-2 pt-2 border-top border-light">
                                <i class="bx bx-time-five me-1"></i>
                                <span>Ajouté {{ $document->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-label-secondary rounded-circle mb-3" style="width: 80px; height: 80px;">
                    <i class="bx bx-folder-open text-muted" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="text-muted">Aucun document disponible</h5>
                <p class="text-muted mb-0">Les documents ajoutés apparaîtront ici.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
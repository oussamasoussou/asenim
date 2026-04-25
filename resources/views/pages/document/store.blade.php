@extends('layouts.app')

@section('title', 'Ajouter un document')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('documents.non_archived') }}" class="btn btn-icon btn-light rounded-circle shadow-sm me-3">
            <i class="bx bx-left-arrow-alt fs-4"></i>
        </a>
        <div>
            <h4 class="mb-1 text-dark fw-bold">Nouveau Document</h4>
            <p class="text-muted mb-0">Téléversez un fichier pour le rendre disponible sur la plateforme.</p>
        </div>
    </div>

    <div class="row align-items-start">
        <div class="col-lg-8">
            <div class="card border-0 shadow-md rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom border-light py-4 px-4 d-flex align-items-center">
                    <div class="bg-label-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bxs-file-pdf text-primary fs-3"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Détails de la ressource</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4 mb-4">
                            <!-- Nom du fichier -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="file_name">Nom d'affichage du fichier</label>
                                <div class="input-container w-100 bg-light border @error('file_name') border-danger @enderror">
                                    <i class="bx bx-file text-muted fs-5 ps-1 pe-2"></i>
                                    <input type="text" class="w-100 bg-transparent border-0" id="file_name" name="file_name" value="{{ old('file_name') }}" placeholder="Ex: Rapport Annuel 2024">
                                </div>
                                <div class="form-text mt-1 text-muted"><i class="bx bx-info-circle"></i> Ce nom sera visible par les membres cherchant ce fichier.</div>
                                @error('file_name') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Type de membre -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="member_type">Restriction d'accès (Visibilité)</label>
                                <div class="position-relative">
                                    <select id="member_type" name="member_type" class="form-select bg-light border shadow-none py-2 px-3 rounded-3 @error('member_type') border-danger @enderror" style="height: 48px; appearance: auto;">
                                        <option value="all_members" {{ old('member_type') == 'all_members' ? 'selected' : '' }}>Public (Tous les membres) - Visible par toute personne connectée</option>
                                        <option value="permanent" {{ old('member_type') == 'permanent' ? 'selected' : '' }}>Réservé au GDI</option>
                                        <option value="non_permanent" {{ old('member_type') == 'non_permanent' ? 'selected' : '' }}>Réservé aux membres non-permanents</option>
                                    </select>
                                </div>
                                @error('member_type') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Upload du fichier -->
                            <div class="col-12 mt-4">
                                <label class="form-label fw-semibold" for="file">Fichier (PDF, Images etc.)</label>
                                <div class="border rounded-4 p-5 text-center bg-light border-dashed position-relative d-flex justify-content-center align-items-center flex-column" style="min-height: 180px;">
                                    <i class="bx bx-cloud-upload text-primary mb-3" style="font-size: 3.5rem;"></i>
                                    <h6 class="fw-bold mb-1">Cliquez ou glissez un fichier ici</h6>
                                    <p class="mb-0 text-muted small">Formats supportés : .pdf, .jpg, .png... (Taille max autorisée par le serveur)</p>
                                    <input class="form-control position-absolute w-100 h-100 opacity-0 cursor-pointer" type="file" id="file" name="file" accept="application/pdf, image/*" style="top:0; left:0; z-index: 10;">
                                </div>
                                @error('file') <span class="text-danger small mt-2 d-block text-center"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-top d-flex justify-content-end gap-3">
                            <a href="{{ route('documents.non_archived') }}" class="btn btn-light rounded-pill px-4 fw-medium text-muted hover-lift">Annuler</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow fw-semibold hover-lift" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                                <i class="bx bx-cloud-upload me-1"></i> Publier le document
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-info-circle fs-3 text-info me-2"></i>
                        <h5 class="mb-0 text-white fw-bold">Sécurité</h5>
                    </div>
                    <p style="font-size: 0.9rem; opacity: 0.85;">
                        Les documents téléversés sont stockés de manière sécurisée sur le serveur. Seuls les membres de la plateforme ayant le rôle ou le statut approprié (défini par le paramètre de "Visibilité") pourront les télécharger depuis leur espace membre.
                    </p>
                    <p class="mb-0" style="font-size: 0.85rem; opacity: 0.7;">
                        <i class="bx bx-shield-check me-1"></i> Accès contrôlé
                    </p>
                </div>
                <!-- Decorative element -->
                <div class="position-absolute" style="top: -20px; right: -20px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(14,165,233,0.3) 0%, rgba(14,165,233,0) 70%); border-radius: 50%;"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* Utility for dashed border in upload area */
.border-dashed { border-style: dashed !important; border-width: 2px !important; }
.hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.hover-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection

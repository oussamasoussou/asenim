@extends('layouts.app')

@section('title', 'Publier une Actualité ou Événement')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('news.index') }}" class="btn btn-icon btn-light rounded-circle shadow-sm me-3">
            <i class="bx bx-left-arrow-alt fs-4"></i>
        </a>
        <div>
            <h4 class="mb-1 text-dark fw-bold">Rédiger un article / Événement</h4>
            <p class="text-muted mb-0">Partagez l'information avec l'ensemble des membres de la plateforme.</p>
        </div>
    </div>

    <div class="row align-items-start">
        <div class="col-lg-8">
            <div class="card border-0 shadow-md rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom border-light py-4 px-4 d-flex align-items-center">
                    <div class="bg-label-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-edit-alt text-primary fs-3"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Contenu de la publication</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Titre -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="title">Titre de la publication</label>
                            <input class="form-control form-control-lg bg-light border shadow-none px-3 py-3 rounded-3 @error('title') is-invalid border-danger @enderror" type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Ex: Assemblée Générale Annuelle" />
                            @error('title') <div class="invalid-feedback mt-2"><i class="bx bx-error-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <!-- Type & Date Row -->
                        <div class="row g-4 mb-4">
                            <!-- Type -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="events_news">Type de publication</label>
                                <select class="form-select bg-light border shadow-none py-2 px-3 rounded-3 @error('events_news') is-invalid border-danger @enderror" id="events_news" name="events_news" style="height: 52px; appearance: auto;">
                                    <option value="">Sélectionner un type...</option>
                                    <option value="news" {{ old('events_news') == 'news' ? 'selected' : '' }}>🗞️ Simple Actualité</option>
                                    <option value="event" {{ old('events_news') == 'event' ? 'selected' : '' }}>📅 Événement programmé</option>
                                </select>
                                @error('events_news') <div class="invalid-feedback"><i class="bx bx-error-circle"></i> {{ $message }}</div> @enderror
                            </div>

                            <!-- Date (pour event) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="date">Date (si événement)</label>
                                <input class="form-control bg-light border shadow-none px-3 rounded-3 @error('date') is-invalid border-danger @enderror" type="datetime-local" id="date" name="date" value="{{ old('date') }}" style="height: 52px;" />
                                @error('date') <div class="invalid-feedback"><i class="bx bx-error-circle"></i> {{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Contenu riche -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="content">Corps du texte</label>
                            <textarea id="content" name="content" class="form-control bg-light border shadow-none p-3 rounded-3 @error('content') is-invalid border-danger @enderror" rows="6" placeholder="Rédigez le contenu ici...">{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback"><i class="bx bx-error-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <!-- Image Cover -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="image">Image de couverture (Optionnelle mais recommandée)</label>
                            <div class="border rounded-4 p-4 text-center bg-light border-dashed position-relative d-flex justify-content-center align-items-center flex-column" style="min-height: 140px;">
                                <i class="bx bxs-image text-primary mb-2" style="font-size: 3rem;"></i>
                                <h6 class="fw-bold mb-1">Ajouter une image d'illustration</h6>
                                <p class="mb-0 text-muted small">Cliquez ou glissez une image ici (.jpg, .png)</p>
                                <input class="form-control position-absolute w-100 h-100 opacity-0 cursor-pointer" type="file" id="image" name="image" accept="image/*" style="top:0; left:0; z-index: 10;">
                            </div>
                            @error('image') <span class="text-danger small mt-2 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-top d-flex justify-content-end gap-3">
                            <a href="{{ route('news.index') }}" class="btn btn-light rounded-pill px-4 fw-medium text-muted hover-lift">Annuler</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow fw-semibold hover-lift" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                                <i class="bx bx-send me-1"></i> Publier
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden border">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-bulb fs-3 text-warning me-2"></i>
                        <h5 class="mb-0 fw-bold">Conseils de publication</h5>
                    </div>
                    <ul class="list-unstyled mb-0 text-muted" style="font-size: 0.9rem;">
                        <li class="mb-3">
                            <span class="text-primary fw-bold">Actualité vs Événement :</span> 
                            Une <strong>actualité</strong> est une simple information. Un <strong>événement</strong> requiert obligatoirement une date et sera mis en avant sur le tableau de bord.
                        </li>
                        <li class="mb-3">
                            <span class="text-primary fw-bold">Image :</span> Les publications avec image attirent 3x plus l'attention sur la page d'accueil !
                        </li>
                        <li>
                            <span class="text-primary fw-bold">Modification :</span> Vous pourrez modifier ou archiver cette publication après l'avoir postée.
                        </li>
                    </ul>
                </div>
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
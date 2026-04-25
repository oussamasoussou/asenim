@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('users.index') }}" class="btn btn-icon btn-light rounded-circle shadow-sm me-3">
            <i class="bx bx-left-arrow-alt fs-4"></i>
        </a>
        <div>
            <h4 class="mb-1 text-dark fw-bold">Nouveau Compte</h4>
            <p class="text-muted mb-0">Remplissez les informations ci-dessous pour créer un nouvel utilisateur.</p>
        </div>
    </div>

    <div class="row align-items-start">
        <div class="col-lg-8">
            <div class="card border-0 shadow-md rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom border-light py-4 px-4 d-flex align-items-center">
                    <div class="bg-label-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bx bx-user-plus text-primary fs-3"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Informations Utilisateur</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('create-user') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <!-- Prénom -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="first_name">Prénom</label>
                                <div class="input-container w-100 bg-light border @error('first_name') border-danger @enderror">
                                    <i class="bx bx-user text-muted fs-5 ps-1 pe-2"></i>
                                    <input type="text" class="w-100 bg-transparent border-0" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Ex: Jean">
                                </div>
                                @error('first_name') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Nom -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="last_name">Nom</label>
                                <div class="input-container w-100 bg-light border @error('last_name') border-danger @enderror">
                                    <i class="bx bx-buildings text-muted fs-5 ps-1 pe-2"></i>
                                    <input type="text" class="w-100 bg-transparent border-0" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Ex: Dupont">
                                </div>
                                @error('last_name') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="email">Adresse Email</label>
                                <div class="input-container w-100 bg-light border @error('email') border-danger @enderror">
                                    <i class="bx bx-envelope text-muted fs-5 ps-1 pe-2"></i>
                                    <input type="email" class="w-100 bg-transparent border-0" id="email" name="email" value="{{ old('email') }}" placeholder="jean.dupont@email.com">
                                </div>
                                @error('email') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="phone">Numéro de téléphone</label>
                                <div class="input-container w-100 bg-light border @error('phone') border-danger @enderror">
                                    <i class="bx bx-phone text-muted fs-5 ps-1 pe-2"></i>
                                    <input type="text" class="w-100 bg-transparent border-0 phone-mask" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+33 6 00 00 00 00">
                                </div>
                                @error('phone') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Rôle -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="role">Rôle d'accès</label>
                                <div class="position-relative">
                                    <select id="role" name="role" class="form-select bg-light border shadow-none py-2 px-3 rounded-3 @error('role') border-danger @enderror" style="height: 44px; appearance: auto;">
                                        <option value="membre" {{ old('role') == 'membre' ? 'selected' : '' }}>Membre Standard</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    </select>
                                </div>
                                @error('role') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Type -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="type">Statut contractuel</label>
                                <div class="position-relative">
                                    <select id="type" name="type" class="form-select bg-light border shadow-none py-2 px-3 rounded-3 @error('type') border-danger @enderror" style="height: 44px; appearance: auto;">
                                        <option value="permanent" {{ old('type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="non_permanent" {{ old('type') == 'non_permanent' ? 'selected' : '' }}>Non Permanent</option>
                                    </select>
                                </div>
                                @error('type') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-12 mt-4">
                                <label class="form-label fw-semibold" for="image">Photo de Profil (Optionnelle)</label>
                                <div class="border rounded-4 p-4 text-center bg-light border-dashed position-relative d-flex justify-content-center align-items-center flex-column" style="min-height: 120px;">
                                    <i class="bx bx-cloud-upload text-primary mb-2" style="font-size: 2.5rem;"></i>
                                    <p class="mb-0 text-muted small">Cliquez ou glissez une image ici</p>
                                    <input class="form-control position-absolute w-100 h-100 opacity-0 cursor-pointer" type="file" id="image" name="image" accept="image/*" style="top:0; left:0; z-index: 10;">
                                </div>
                                @error('image') <span class="text-danger small mt-1 d-block"><i class="bx bx-error-circle"></i> {{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-3">
                            <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4 fw-medium text-muted hover-lift">Annuler</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow fw-semibold hover-lift" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                                <i class="bx bx-check-circle me-1"></i> Enregistrer
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bx bx-info-circle fs-3 me-2"></i>
                        <h5 class="mb-0 text-white fw-bold">À propos des rôles</h5>
                    </div>
                    <ul class="list-unstyled mb-0" style="font-size: 0.9rem; opacity: 0.9;">
                        <li class="mb-3">
                            <strong>Administrateur :</strong> A accès total à l'interface, peut gérer les utilisateurs, supprimer des actualités et documents.
                        </li>
                        <li>
                            <strong>Membre Standard :</strong> Ne peut consulter et créer que ses propres éléments. Accès limité aux paramètres globaux.
                        </li>
                    </ul>
                </div>
                <!-- Decorative background elements -->
                <i class="bx bxs-quote-alt-right position-absolute" style="font-size: 8rem; opacity: 0.05; bottom: -20px; right: -20px;"></i>
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

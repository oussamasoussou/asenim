@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="container mt-5">
  <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="infos-personnelles-tab" data-bs-toggle="tab" data-bs-target="#infos-personnelles"
        role="tab">Infos personnelles</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="infos-pro-tab" data-bs-toggle="tab" data-bs-target="#infos-pro" role="tab">Infos
        professionnelles</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="biographie-tab" data-bs-toggle="tab" data-bs-target="#biographie" role="tab">Biographie &
        Activités</a>
    </li>
  </ul>

  <form method="POST" action="{{ route('user.update.connected.membre.user', $user->id) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="tab-content mt-4" id="myTabContent">
      <!-- Onglet Infos personnelles -->
      <div class="tab-pane fade show active" id="infos-personnelles" role="tabpanel">
        <div class="card shadow-sm mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Infos personnelles</h5>
          </div>
          <div class="card-body">
            <div class="mb-4">
              <label for="fileInput" class="form-label fw-bold">Photo de profil</label>
              <div class="d-flex align-items-center">
                <!-- Afficher l'image actuelle -->
                <div class="me-4">
                  @if($user->image)
                          <img src="{{ asset('storage/' . $user->image) }}" alt="Image actuelle"
                          class="rounded-circle shadow-sm" style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                      <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm"
                      style="width: 140px; height: 140px;">
                      <i class="fas fa-user fa-3x text-secondary"></i>
                      </div>
                    @endif
                </div>
                <!-- Bouton pour télécharger une nouvelle image -->
                <div>
                  <input type="file" id="fileInput" name="image" class="d-none" accept="image/*">
                  <label for="fileInput" class="btn btn-outline-primary">
                    <i class="fas fa-upload me-2"></i>Changer la photo
                  </label>
                  <p class="text-muted mt-2">Formats supportés : JPEG, PNG, JPG. Taille max : 2MB.</p>
                </div>
              </div>
              <!-- Aperçu de la nouvelle image sélectionnée -->
              <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                <img id="preview" src="#" alt="Aperçu de la nouvelle image" class="rounded-circle shadow-sm"
                  style="width: 140px; height: 140px; object-fit: cover;">
              </div>
            </div>

            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-user"></i></span>
                <input type="text" class="form-control" id="firstname" name="first_name"
                  value="{{ $user->first_name }}" />
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-user"></i></span>
                <input type="text" class="form-control" id="lastname" name="last_name" value="{{ $user->last_name }}" />
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" placeholder="john.doe@example.com"
                  value="{{ $user->email }}" />
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="658 799 8941"
                  value="{{ $user->phone }}" />
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-buildings"></i></span>
                <input type="text" class="form-control" id="institution" name="institution" placeholder="ACME Inc."
                  value="{{ $user->institution }}" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Onglet Infos professionnelles -->
      <div class="tab-pane fade" id="infos-pro" role="tabpanel">
        <div class="card shadow-sm mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Infos professionnelles</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label" for="grade">Grade</label>
              <select class="form-select" id="grade" name="grade">
                <option>Choisir un grade</option>
                <option value="1" {{ $user->grade == 1 ? 'selected' : '' }}>Grade 1</option>
                <option value="2" {{ $user->grade == 2 ? 'selected' : '' }}>Grade 2</option>
                <option value="3" {{ $user->grade == 3 ? 'selected' : '' }}>Grade 3</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="orcid">Orcid</label>
              <div class="input-group">
                <input type="text" class="form-control" id="orcid" name="orcid" placeholder="John Doe"
                  value="{{ $user->orcid }}" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="fonction">Fonction</label>
              <div class="input-group">
                <input type="text" class="form-control" id="fonction" name="function" placeholder="Fonction"
                  value="{{ $user->function }}" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Onglet Biographie & Activités -->
      <div class="tab-pane fade" id="biographie" role="tabpanel">
        <div class="card shadow-sm mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Biographie & Activités</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label for="biographyTextarea" class="form-label">Biographie</label>
              <textarea class="form-control" id="biographyTextarea" name="biography"
                rows="3">{{ $user->biography }}</textarea>
            </div>
            <div class="mb-3">
              <label for="activitiesTextarea" class="form-label">Activités</label>
              <textarea class="form-control" id="activitiesTextarea" name="activities"
                rows="3">{{ $user->activities }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Bouton de soumission du formulaire -->
    <div class="text-end mt-4">
      <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </div>
  </form>
</div>

<!-- Formulaire principal -->




@endsection
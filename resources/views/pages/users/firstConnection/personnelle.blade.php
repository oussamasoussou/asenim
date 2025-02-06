@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<h4 class="py-3 mb-4">Paramètres du compte </h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Infos
                    personnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.edit.professionnelle.membre.first') }}" ><i class='bx bx-align-justify'></i>
                    Infos professionnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.edit.biography.membre.first') }}" ><i class='bx bxs-book-content'></i>
                    Biographie & Activités</a>
            </li>
        </ul>
      
        <div class="card mb-4">
            <h5 class="card-header">Profile Details</h5>
            <form method="POST" action="{{ route('user.update.personnel.membre.first') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{ asset('storage/' . $userConnected->image) }}" alt="user-avatar" class="d-block rounded"
                            height="100" width="100" id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Télécharger une nouvelle photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="image" class="account-file-input" hidden
                                    accept="image/png, image/jpeg" />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Réinitialiser</span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label" for="first_name">Prénom</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="Prénom" value="{{ old('first_name', $userConnected->first_name) }}">
                            </div>
                            @error('first_name')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="last_name">Nom</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Nom" value="{{ old('last_name', $userConnected->last_name) }}">
                            </div>
                            @error('last_name')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Adresse email" value="{{ old('email', $userConnected->email) }}">
                            </div>
                            @if(session('error'))
                                <span class="text-danger">* {{ session('error') }}</span>
                            @endif
                            @if(session('email_error'))
                                <span class="text-danger">* {{ session('email_error') }}</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Numéro de téléphone</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-phone2" class="input-group-text"><i
                                        class="bx bx-phone"></i></span>
                                <input type="text" id="phone" name="phone" class="form-control phone-mask"
                                    placeholder="Numéro de téléphone" value="{{ old('phone', $userConnected->phone) }}">
                            </div>
                            @error('phone')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                            @if(session('phone_error'))
                                <span class="text-danger">* {{ session('phone_error') }}</span>
                            @endif
                        </div>


                        <div class="mb-3">
                            <label for="type" class="form-label">Type de membre</label>
                            <select id="type" name="type" class="form-control">
                                <option value="all_members" {{ old('type', $userConnected->type) == 'all_members' ? 'selected' : '' }}>
                                    Tous les membres
                                </option>
                                <option value="permanent" {{ old('type', $userConnected->type) == 'permanent' ? 'selected' : '' }}>
                                    Permanent
                                </option>
                                <option value="non_permanent" {{ old('type', $userConnected->type) == 'non_permanent' ? 'selected' : '' }}>
                                    Non Permanent
                                </option>
                            </select>
                            @error('type')
                                <span class="text-danger">* {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Mettre à jour (Infos personnelles)</button>
                            <button type="reset" class="btn btn-outline-secondary">Annuler</button>
                        </div>
                    </div>
            </form>
        </div>
        <!-- /Account -->
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('upload');
        const imagePreview = document.getElementById('uploadedAvatar');

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Gestion du bouton "Reset"
        const resetButton = document.querySelector('.account-image-reset');
        resetButton.addEventListener('click', function () {
            fileInput.value = ''; // Réinitialiser le champ de fichier
            imagePreview.src = "{{ asset('storage/' . $userConnected->image) }}"; // Réinitialiser l'image à l'ancienne valeur
        });
    });
</script>
@endsection
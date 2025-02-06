@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<h4 class="py-3 mb-4">Paramètres du compte </h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.edit.personnel.membre.first') }}"><i class="bx bx-user me-1"></i>
                    Infos
                    personnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.edit.professionnelle.membre.first') }}"><i
                        class='bx bx-align-justify'></i>
                    Infos professionnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"><i class='bx bxs-book-content'></i>
                    Biographie & Activités</a>
            </li>
        </ul>
        <div class="card mb-4">
            <h5 class="card-header">Profile Details</h5>
            <form method="POST" action="{{ route('user.edit.biography.membre.first') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <hr class="my-0" />
                <div class="card-body">


                    <div class="row">
                        <div class="mb-3">
                            <label for="biographyTextarea" class="form-label">Biographie</label>
                            <textarea class="form-control" id="biographyTextarea" name="biography"
                                rows="3">{{ $userConnected->biography }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="activitiesTextarea" class="form-label">Activités</label>
                            <textarea class="form-control" id="activitiesTextarea" name="activities"
                                rows="3">{{ $userConnected->activities }}</textarea>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Mettre à jour (Biographie & Activités)</button>
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
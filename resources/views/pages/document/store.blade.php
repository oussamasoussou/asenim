@extends('layouts.app')

@section('title', 'Ajouter une Actualité/Événement')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter une Actualité/Événement</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Champ Titre -->
                <div class="mb-3 row">
                    <label for="title" class="col-md-2 col-form-label">Titre</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="title" id="title" placeholder="Titre de l'actualité ou événement" required />
                    </div>
                </div>

                <!-- Champ Sélection Type -->
                <div class="mb-3 row">
                    <label for="type" class="col-md-2 col-form-label">Type</label>
                    <div class="col-md-10">
                        <select class="form-control" name="type" id="type" required>
                            <option value="actualite">Actualité</option>
                            <option value="evenement">Événement</option>
                        </select>
                    </div>
                </div>

                <!-- Champ Contenu avec CKEditor -->
                <div class="mb-3 row">
                    <label for="content" class="col-md-2 col-form-label">Contenu</label>
                    <div class="col-md-10">
                        <textarea id="content" name="content" class="form-control" rows="5" placeholder="Contenu de l'actualité ou événement" required></textarea>
                    </div>
                </div>

                <!-- Champ Date -->
                <div class="mb-3 row">
                    <label for="date" class="col-md-2 col-form-label">Date</label>
                    <div class="col-md-10">
                        <input class="form-control" type="datetime-local" name="date" id="date" required />
                    </div>
                </div>

                <!-- Champ Image -->
                <div class="mb-3 row">
                    <label for="image" class="col-md-2 col-form-label">Image</label>
                    <div class="col-md-10">
                        <input class="form-control" type="file" name="image" id="image" />
                    </div>
                </div>

                <!-- Bouton Sauvegarder -->
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </form>
        </div>
    </div>
</div>
@endsection

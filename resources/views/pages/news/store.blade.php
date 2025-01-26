@extends('layouts.app')

@section('title', 'Ajouter une Actualité / Événement')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ajouter une Actualité / Événement</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Champ Titre -->
                <div class="mb-3 row">
                    <label for="title" class="col-md-2 col-form-label">Titre</label>
                    <div class="col-md-10">
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                            name="title" value="{{ old('title') }}" placeholder="Entrez le titre" />
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Champ Type -->
                <div class="mb-3 row">
                    <label for="events_news" class="col-md-2 col-form-label">Type</label>
                    <div class="col-md-10">
                        <select class="form-control @error('events_news') is-invalid @enderror" id="events_news"
                            name="events_news">
                            <option value="">Sélectionner...</option>
                            <option value="news" {{ old('events_news') == 'news' ? 'selected' : '' }}>Actualité</option>
                            <option value="event" {{ old('events_news') == 'event' ? 'selected' : '' }}>Événement</option>
                        </select>
                        @error('events_news')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <!-- Champ Contenu avec CKEditor -->
                <div class="mb-3 row">
                    <label for="content" class="col-md-2 col-form-label">Contenu</label>
                    <div class="col-md-10">
                        <textarea id="content" name="content"
                            class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Champ Date -->
                <div class="mb-3 row">
                    <label for="date" class="col-md-2 col-form-label">Date</label>
                    <div class="col-md-10">
                        <input class="form-control @error('date') is-invalid @enderror" type="datetime-local" id="date"
                            name="date" value="{{ old('date') }}" />
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Champ Image -->
                <div class="mb-3 row">
                    <label for="image" class="col-md-2 col-form-label">Image</label>
                    <div class="col-md-10">
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                            name="image" />
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bouton Sauvegarder -->
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </form>
        </div>
    </div>
</div>

@endsection
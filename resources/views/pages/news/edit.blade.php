@extends('layouts.app')

@section('title', 'Modifier un événement/actualite')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Modifier un événement/actualité</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('news.update', $news->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3 row">
                    <label for="title" class="col-md-2 col-form-label">Titre</label>
                    <div class="col-md-10">
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title" name="title" value="{{ old('title', $news->title) }}" />
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="events_news" class="col-md-2 col-form-label">Type</label>
                    <div class="col-md-10">
                        <select class="form-control @error('events_news') is-invalid @enderror" id="events_news" name="events_news">
                            <option value="news" {{ old('events_news', $news->events_news) == 'news' ? 'selected' : '' }}>Actualité</option>
                            <option value="event" {{ old('events_news', $news->events_news) == 'event' ? 'selected' : '' }}>Événement</option>
                        </select>
                        @error('events_news')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="content" class="col-md-2 col-form-label">Contenu</label>
                    <div class="col-md-10">
                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', $news->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="image" class="col-md-2 col-form-label">Image</label>
                    <div class="col-md-10">
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                        @if($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" alt="Image actuelle" class="mt-2" style="max-width: 200px;">
                        @endif
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
            </form>
        </div>
    </div>
</div>
@endsection

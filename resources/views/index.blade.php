@extends('layouts.app')

@section('title', 'Liste des utilisateurs, événements, actualités et documents')

@section('content')
<div class="container">

    <!-- Section Utilisateurs -->
    <h4 class="mb-4">Liste des Utilisateurs</h4>
    <div class="row mb-5">
        @foreach($users as $index => $user)
            <div class="col-md-6 mb-4">
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0">
                        @if($index % 2 == 0)
                            <div class="col-md-4">
                                <img class="card-img card-img-left rounded-start"
                                    src="{{ asset('storage/' . ($user->image ?? 'default.jpg')) }}"
                                    alt="Image utilisateur">
                            </div>
                        @endif
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="card-text">
                                    <strong>Email:</strong> {{ $user->email }} <br>
                                    <strong>Date d'inscription:</strong> {{ $user->created_at->format('d/m/Y') }}
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">Dernière mise à jour : {{ $user->updated_at->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>
                        @if($index % 2 != 0)
                            <div class="col-md-4">
                                <img class="card-img card-img-right rounded-end"
                                    src="{{ asset('storage/' . ($user->image ?? 'default.jpg')) }}"
                                    alt="Image utilisateur">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Section Actualités -->
    <h4 class="mb-4">Liste des Actualités</h4>
    <div class="row mb-5">
        @foreach($news as $item)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text">
                            <strong>Créé par:</strong> {{ $item->user->name }}<br>
                            <strong>Date:</strong> {{ $item->created_at->format('d/m/Y') }}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Publié {{ $item->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Section Documents -->
    <h4 class="mb-4">Liste des Documents</h4>
    <div class="row mb-5">
        @foreach($documents as $document)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $document->name }}</h5>
                        <p class="card-text">
                            <strong>Type:</strong> {{ $document->type }}<br>
                            <strong>Date d'ajout:</strong> {{ $document->created_at->format('d/m/Y') }}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Ajouté {{ $document->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

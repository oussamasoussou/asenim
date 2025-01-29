@extends('layouts.app')

@section('title', 'Liste des utilisateurs, événements, actualités et documents')

@section('content')
<div class="container">

    <!-- Section Utilisateurs
    <h4 class="mb-4">Liste des Utilisateurs</h4>
    <div class="row mb-5">
        @foreach($users as $index => $user)
            <div class="col-md-6 mb-4">
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0">
                        @if($index % 2 == 0)
                            <div class="col-md-4">
                                <img class="card-img card-img-left rounded-start"
                                    src="{{ asset('storage/' . ($user->image ?? 'default.jpg')) }}" alt="Image utilisateur"
                                    style="width: 150px; height: 180px; object-fit: cover;">
                            </div>
                        @endif
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                <p class="card-text">
                                    <strong>Email:</strong> {{ $user->email }} <br>
                                    <strong>Date d'inscription:</strong> {{ $user->created_at->format('d/m/Y') }}
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">Dernière mise à jour :
                                        {{ $user->updated_at->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>
                        @if($index % 2 != 0)
                            <div class="col-md-4">
                                <img class="card-img card-img-right rounded-end"
                                    src="{{ asset('storage/' . ($user->image ?? 'default.jpg')) }}" alt="Image utilisateur"
                                    style="width: 150px; height: 180px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div> -->


    <!-- Section Actualités -->
    <h4 class="mb-4">Liste des Actualités</h4>
    <div class="grid-container">
        @foreach($news as $item)
            <div class="card">
                <div class="card-body">
                <h5 class="card-title {{ $item->date < now() ? ' text-red' : '' }}">{{ $item->title }}</h5>
                <p class="card-text">
                        <strong>Créé par:</strong> {{ $item->user->first_name }} {{ $item->user->last_name }}<br>
                        <strong>Date:</strong> {{ $item->date }}
                    </p>
                    <p class="card-text">
                        <small>Publié {{ $item->created_at->diffForHumans() }}</small>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="#">Voir plus</a>
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
                    <div class="card-body text-center">
                        <!-- Icône ou aperçu du fichier cliquable -->
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="d-block">
                                @if(in_array($document->type, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('storage/' . $document->file_path) }}" class="img-fluid rounded"
                                        alt="Aperçu" style="max-height: 150px;">
                                @elseif(in_array($document->type, ['pdf', 'application/pdf']))
                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                @else
                                    <i class="fas fa-file-alt fa-3x text-primary"></i>
                                @endif
                            </a>
                        </div>

                        <h5 class="card-title">{{ $document->file_name }}</h5>
                        <p class="card-text">
                            <strong>Date d'ajout:</strong> {{ $document->created_at->format('d/m/Y') }}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Ajouté {{ $document->created_at->diffForHumans() }} </small> <br>
                            <small class="text-muted">Créé par: {{$document->user->first_name}}
                                {{$document->user->last_name}} </small>
                        </p>

                    </div>
                </div>
            </div>
        @endforeach
    </div>


</div>
@endsection
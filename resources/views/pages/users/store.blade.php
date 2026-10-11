@extends('layouts.app')

@section('title', 'Nouvel utilisateur')

@section('content')
    <x-page-header title="Nouvel utilisateur" :back="route('users.index')"
        subtitle="Le compte est créé avec un mot de passe provisoire ; le membre complétera son profil à sa première connexion." />

    <form method="POST" action="{{ route('create-user') }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @include('pages.users.partials.personal-fields', ['subject' => null, 'withRole' => true])

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Accès initial</h2>
                <p class="form-section-text">Informations à transmettre au nouvel utilisateur.</p>
            </div>
            <div class="alert alert-info mb-0">
                <i class="bx bx-info-circle"></i>
                <div>Le mot de passe provisoire est <strong>123456789</strong>. Le membre sera invité à compléter son profil lors de sa première connexion ; recommandez-lui de changer son mot de passe depuis l’onglet « Sécurité ».</div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Créer le compte</span></button>
        </div>
    </form>
@endsection

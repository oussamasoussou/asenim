@extends('layouts.app')

@section('title', 'Sécurité du compte')

@section('content')
    @include('pages.users.partials.self-header')
    <x-profile-tabs active="security" />

    <form method="POST" action="{{ route('users.update.password') }}" class="card" id="formAccountSettings" novalidate>
        @csrf
        @method('PUT')

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Mot de passe</h2>
                <p class="form-section-text">Utilisez au moins 8 caractères. Un mélange de lettres, chiffres et symboles est recommandé.</p>
            </div>
            <div style="max-width: 460px;">
                <x-field name="current_password" type="password" label="Mot de passe actuel" required autocomplete="current-password" />
                <x-field name="new_password" type="password" label="Nouveau mot de passe" required autocomplete="new-password" minlength="8"
                    hint="8 caractères minimum." />
                <x-field name="new_password_confirmation" type="password" label="Confirmer le nouveau mot de passe" required autocomplete="new-password" />
            </div>
        </div>

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Session</h2>
                <p class="form-section-text">Informations sur votre accès actuel.</p>
            </div>
            <dl class="kv mb-0">
                <dt>Compte</dt>
                <dd>{{ $userConnected->email }}</dd>
                <dt>Rôle</dt>
                <dd>{{ $userConnected->isAdmin() ? 'Administrateur' : 'Membre' }}</dd>
                <dt>Membre depuis</dt>
                <dd>{{ $userConnected->created_at?->translatedFormat('j F Y') }}</dd>
            </dl>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-lock-alt"></i> Mettre à jour le mot de passe</span></button>
        </div>
    </form>
@endsection

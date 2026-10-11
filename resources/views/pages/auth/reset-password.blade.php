@extends('layouts.auth')

@section('title', 'Nouveau mot de passe')

@section('content')
    <h1>Nouveau mot de passe</h1>
    <p class="lead">Choisissez un mot de passe d’au moins 8 caractères.</p>

    <form id="formAuthentication" method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-field name="email" type="email" label="Adresse e-mail" icon="bx-envelope" :value="$email ?? request('email')" required autocomplete="username" />
        <x-field name="password" type="password" label="Nouveau mot de passe" required autocomplete="new-password" />
        <x-field name="password_confirmation" type="password" label="Confirmer le mot de passe" required autocomplete="new-password" />
        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2"><span>Réinitialiser le mot de passe</span></button>
    </form>

    <a href="{{ route('login') }}" class="btn btn-ghost w-100 mt-3"><i class="bx bx-arrow-back"></i>Retour à la connexion</a>
@endsection

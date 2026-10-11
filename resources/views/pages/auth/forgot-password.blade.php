@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
    <h1>Mot de passe oublié ?</h1>
    <p class="lead">Indiquez votre adresse e-mail : nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>

    @if (session('status'))
        <div class="alert alert-success mb-3" role="status"><i class="bx bx-check-circle"></i><div>{{ session('status') }}</div></div>
    @endif

    <form id="formAuthentication" action="{{ route('password.email') }}" method="POST" novalidate>
        @csrf
        <x-field name="email" type="email" label="Adresse e-mail" icon="bx-envelope" required autofocus autocomplete="email" placeholder="vous@exemple.com" />
        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2"><span>Envoyer le lien</span></button>
    </form>

    <a href="{{ route('login') }}" class="btn btn-ghost w-100 mt-3"><i class="bx bx-arrow-back"></i>Retour à la connexion</a>
@endsection

@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
    <h1>Connexion</h1>
    <p class="lead">Accédez à votre espace avec vos identifiants.</p>

    @if (session('status') || session('success'))
        <div class="alert alert-success mb-3" role="status"><i class="bx bx-check-circle"></i><div>{{ session('status') ?? session('success') }}</div></div>
    @endif
    @if (session('error') || $errors->has('error'))
        <div class="alert alert-danger mb-3" role="alert"><i class="bx bx-error-circle"></i><div>{{ session('error') ?? $errors->first('error') }}</div></div>
    @endif

    <form id="formAuthentication" action="{{ route('login') }}" method="POST" novalidate>
        @csrf
        <x-field name="email" type="email" label="Adresse e-mail" icon="bx-envelope" required autofocus autocomplete="username" placeholder="vous@exemple.com" />

        <div class="d-flex justify-content-between align-items-baseline">
            <label class="form-label" for="password">Mot de passe</label>
            <a href="{{ route('password.request') }}" class="small fw-semibold">Mot de passe oublié ?</a>
        </div>
        <x-field name="password" type="password" required autocomplete="current-password" placeholder="••••••••" />

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
            <label class="form-check-label small" for="remember">Rester connecté</label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100"><span>Se connecter</span></button>
    </form>

    <p class="text-muted small mt-4 mb-0">Pas encore de compte ? Contactez un administrateur de la plateforme.</p>
@endsection

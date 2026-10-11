@extends('layouts.app')

@section('title', 'Compléter mon profil')

@section('content')
    @include('pages.users.partials.onboarding-stepper', ['step' => 3])

    <form method="POST" action="{{ route('user.update.biography.membre.first') }}" class="card" novalidate>
        @csrf
        @include('pages.users.partials.biography-fields', ['subject' => $userConnected])

        <div class="form-actions">
            <a href="{{ route('user.edit.professionnelle.membre.first') }}" class="btn btn-ghost me-auto"><i class="bx bx-left-arrow-alt"></i>Retour</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Terminer</span></button>
        </div>
    </form>
@endsection

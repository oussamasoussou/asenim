@extends('layouts.app')

@section('title', 'Compléter mon profil')

@section('content')
    @include('pages.users.partials.onboarding-stepper', ['step' => 2])

    <form method="POST" action="{{ route('user.update.professionnelle.membre.first') }}" class="card" novalidate>
        @csrf
        @include('pages.users.partials.professional-fields', ['subject' => $userConnected])

        <div class="form-actions">
            <a href="{{ route('user.edit.personnel.membre.first') }}" class="btn btn-ghost me-auto"><i class="bx bx-left-arrow-alt"></i>Retour</a>
            <button type="submit" class="btn btn-primary"><span>Continuer <i class="bx bx-right-arrow-alt"></i></span></button>
        </div>
    </form>
@endsection

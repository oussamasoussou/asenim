@extends('layouts.app')

@section('title', 'Compléter mon profil')

@section('content')
    @include('pages.users.partials.onboarding-stepper', ['step' => 1])

    <form method="POST" action="{{ route('user.update.personnel.membre.first') }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @method('PUT')
        @include('pages.users.partials.personal-fields', ['subject' => $userConnected, 'withRole' => false])

        <div class="form-actions">
            <span class="text-muted small me-auto d-none d-sm-inline">Étape 1 sur 3</span>
            <button type="submit" class="btn btn-primary"><span>Continuer <i class="bx bx-right-arrow-alt"></i></span></button>
        </div>
    </form>
@endsection

@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
    @include('pages.users.partials.self-header')
    <x-profile-tabs active="professionnelle" />

    <form method="POST" action="{{ route('users.connected.update.professionnelle') }}" class="card" novalidate>
        @csrf
        @method('PUT')
        @include('pages.users.partials.professional-fields', ['subject' => $userConnected])

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Enregistrer les modifications</span></button>
        </div>
    </form>
@endsection

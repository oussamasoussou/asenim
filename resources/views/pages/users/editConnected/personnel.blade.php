@extends('layouts.app')

@section('title', 'Mon compte')

@section('content')
    @include('pages.users.partials.self-header')
    <x-profile-tabs active="personnel" />

    <form method="POST" action="{{ route('users.connected.update') }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @method('PUT')
        @include('pages.users.partials.personal-fields', ['subject' => $userConnected, 'withRole' => false])

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Enregistrer les modifications</span></button>
        </div>
    </form>
@endsection

@extends('layouts.app')

@section('title', $user->first_name . ' ' . $user->last_name)

@section('content')
    @include('pages.users.partials.admin-header', ['user' => $user])
    <x-profile-tabs active="biography" :user="$user" />

    <form method="POST" action="{{ route('users.update.biography', $user->id) }}" class="card" novalidate>
        @csrf
        @method('PUT')
        @include('pages.users.partials.biography-fields', ['subject' => $user])

        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Enregistrer</span></button>
        </div>
    </form>
@endsection

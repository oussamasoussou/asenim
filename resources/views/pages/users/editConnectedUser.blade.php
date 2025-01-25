@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0 mx-auto text-center" style="font-size:50px">{{ $userConnected->first_name }}
                {{ $userConnected->last_name }}
            </h3>
            <div class="ms-auto">
                <img src="{{ asset('storage/' . $userConnected->image) }}"
                    alt="Photo de profil de {{ $userConnected->first_name }}" class=" img-thumbnail"
                    style="width: 150px; height: 150px; object-fit: cover;">
            </div>
        </div>

        <div class="card-body">

            {{-- Formulaire de modification --}}
            <form method="POST" action="{{ route('users.connected') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Prénom --}}
                <div class="mb-3">
                    <label for="first_name" class="form-label">Prénom</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Prénom"
                            value="{{ old('first_name', $userConnected->first_name) }}">
                    </div>
                    @error('first_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                {{-- Nom --}}
                <div class="mb-3">
                    <label for="last_name" class="form-label">Nom</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nom"
                            value="{{ old('last_name', $userConnected->last_name) }}">
                    </div>
                    @error('last_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Adresse email"
                            value="{{ old('email', $userConnected->email) }}">
                    </div>
                    @error('email')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                    @if(session('email_error'))
                        <span class="text-danger">* {{ session('email_error') }}</span>
                    @endif
                </div>

                {{-- Téléphone --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">Numéro de téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="Numéro de téléphone" value="{{ old('phone', $userConnected->phone) }}">
                    </div>
                    @error('phone')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                    <!-- Spécification des erreurs liées au téléphone -->
                    @if(session('phone_error'))
                        <span class="text-danger">* {{ session('phone_error') }}</span>
                    @endif
                </div>

                {{-- Rôle --}}
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-control">
                        <option value="admin" {{ old('role', $userConnected->role) == 'admin' ? 'selected' : '' }}>Admin
                        </option>
                        <option value="membre" {{ old('role', $userConnected->role) == 'membre' ? 'selected' : '' }}>Membre</option>
                    </select>
                    @error('role')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Photo de profil</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @error('image')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                {{-- Bouton de soumission --}}
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
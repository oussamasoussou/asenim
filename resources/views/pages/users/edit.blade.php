@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Modifier un utilisateur</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="first_name">Prénom</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Prénom"
                            value="{{ old('first_name', $user->first_name) }}">
                    </div>
                    @error('first_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="last_name">Nom</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nom"
                            value="{{ old('last_name', $user->last_name) }}">
                    </div>
                    @error('last_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Adresse email"
                            value="{{ old('email', $user->email) }}">
                    </div>
                    @if(session('error'))
                        <span class="text-danger">* {{ session('error') }}</span>
                    @endif
                    @if(session('email_error'))
                        <span class="text-danger">* {{ session('email_error') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label" for="phone">Numéro de téléphone</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" id="phone" name="phone" class="form-control phone-mask"
                            placeholder="Numéro de téléphone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    @error('phone')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                    @if(session('phone_error'))
                        <span class="text-danger">* {{ session('phone_error') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-control">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="membre" {{ old('role', $user->role) == 'membre' ? 'selected' : '' }}>
                            Membre</option>
                    </select>
                    @error('role')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">URL de l'image</label>
                    <input class="form-control" type="file" id="image" name="image"
                        placeholder="Entrez l'URL de l'image" value="{{ old('image', $user->image) }}">
                    @error('image')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
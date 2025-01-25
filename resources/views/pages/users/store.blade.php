@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="col-xl">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Créer un utilisateur</h5>
        </div>
        <div class="card-body">
                       <form method="POST" action="{{ route('create-user') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="first_name">Prénom</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="{{ old('first_name') }}" placeholder="Prénom">
                    </div>
                    @error('first_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="last_name">Nom</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="{{ old('last_name') }}" placeholder="Nom" >
                    </div>
                    @error('last_name')
                        <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                        <input type="text" id="email" name="email" class="form-control" 
                               value="{{ old('email') }}" placeholder="Adresse email" >
                    </div>
                    @error('email')
                    <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="phone">Numéro de téléphone</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" id="phone" name="phone" class="form-control phone-mask" 
                               value="{{ old('phone') }}" placeholder="Numéro de téléphone" >
                    </div>
                    @error('phone')
                    <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-control" >
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="membre" {{ old('role') == 'membre' ? 'selected' : '' }}>Membre</option>
                    </select>
                    @error('role')
                    <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image de profil</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    @error('image')
                    <span class="text-danger">* {{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection

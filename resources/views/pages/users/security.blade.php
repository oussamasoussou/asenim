@extends('layouts.app')

@section('title', 'Sécurité du compte')

@section('content')
<h4 class="py-3 mb-4">Paramètres du compte</h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.editConnectedUser') }}"><i class="bx bx-user me-1"></i> Infos personnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.connected.edit.professionnelle') }}"><i class='bx bx-align-justify'></i> Infos professionnelles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.connected.edit.biography') }}"><i class='bx bxs-book-content'></i> Biographie & Activités</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"><i class='bx bx-lock-alt'></i> Sécurité</a>
            </li>
        </ul>

        <div class="card mb-4">
            <h5 class="card-header">Changer le mot de passe</h5>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="formAccountSettings" method="POST" action="{{ route('users.update.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="current_password" id="current_password" placeholder="············" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('current_password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" id="new_password" name="new_password" placeholder="············" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('new_password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="············" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <p class="fw-semibold mt-2">Exigences du mot de passe :</p>
                            <ul class="ps-3 mb-0">
                                <li class="mb-1">Minimum 8 caractères</li>
                                <li class="mb-1">Au moins un caractère spécial recommandé</li>
                                <li>Au moins un chiffre recommandé</li>
                            </ul>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Enregistrer les changements</button>
                            <button type="reset" class="btn btn-outline-secondary">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglers = document.querySelectorAll('.input-group-text.cursor-pointer');
    togglers.forEach(toggler => {
        toggler.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bx-hide', 'bx-show');
            } else {
                input.type = 'password';
                icon.classList.replace('bx-show', 'bx-hide');
            }
        });
    });
});
</script>
@endsection

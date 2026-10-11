@extends('layouts.app')

@php $editing = $member->exists; @endphp

@section('title', $editing ? $member->full_name : 'Ajouter un membre')

@section('content')
    <x-page-header :title="$editing ? 'Modifier un membre' : 'Ajouter un membre'" :back="route('board.index')"
        :subtitle="$editing ? $member->full_name . ' · ' . $member->role : 'Le membre apparaîtra à la fin de la liste ; vous pourrez changer son ordre ensuite.'" />

    <form method="POST" action="{{ $editing ? route('board.update', $member->id) : route('board.store') }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Photo</h2>
                <p class="form-section-text">Portrait carré de préférence. JPG, PNG ou WebP, 2 Mo maximum.</p>
            </div>
            <div>
                <div class="avatar-uploader">
                    <img src="{{ $member->photoUrl() }}" alt="" class="avatar-preview" id="avatarPreview">
                    <div>
                        <div class="d-flex gap-2 flex-wrap">
                            <label for="photo" class="btn btn-secondary mb-0">
                                <i class="bx bx-upload"></i>{{ $member->photo ? 'Changer la photo' : 'Importer une photo' }}
                            </label>
                            <button type="button" class="btn btn-ghost" data-avatar-reset="photo">Annuler</button>
                        </div>
                        <input type="file" id="photo" name="photo" accept="image/png, image/jpeg, image/webp" class="d-none" data-avatar-input="#avatarPreview">
                        @if ($member->photo)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_photo" name="remove_photo" value="1">
                                <label class="form-check-label small" for="remove_photo">Supprimer la photo actuelle</label>
                            </div>
                        @endif
                        @error('photo')
                            <div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Identité & fonction</h2>
                <p class="form-section-text">Affichés sur la carte du membre.</p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-field name="full_name" label="Nom complet" :value="$member->full_name" required maxlength="120" placeholder="Ex. Lamis Allegue" />
                </div>
                <div class="col-md-6">
                    <x-field name="role" label="Fonction" :value="$member->role" required maxlength="120" placeholder="Ex. Secrétaire générale" list="board-roles" />
                    <datalist id="board-roles">
                        @foreach (['Président', 'Présidente', 'Vice-président', 'Vice-présidente', 'Secrétaire général', 'Secrétaire générale', 'Trésorier', 'Trésorière', 'Membre'] as $suggestion)
                            <option value="{{ $suggestion }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-6">
                    <x-field name="organization" label="Établissement" icon="bx-buildings" :value="$member->organization" maxlength="120" placeholder="Ex. ENIMonastir" />
                </div>
                <div class="col-md-6">
                    <x-field name="email" type="email" label="E-mail" icon="bx-envelope" :value="$member->email" maxlength="255"
                        placeholder="prenom.nom@exemple.com" hint="Facultatif. Affiché publiquement sur la page « Bureau exécutif »." />
                </div>
            </div>
        </div>

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Citation</h2>
                <p class="form-section-text">Courte phrase affichée sous le nom dans le carrousel de l’accueil.</p>
            </div>
            <div>
                <x-field name="quote" label="Citation" :value="$member->quote" maxlength="200"
                    placeholder="Ex. Créer du lien, aujourd’hui et pour demain." hint="Les guillemets « » sont ajoutés automatiquement." />
            </div>
        </div>

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Visibilité</h2>
                <p class="form-section-text">Un membre masqué reste enregistré mais n’apparaît pas sur le site.</p>
            </div>
            <div>
                <input type="hidden" name="is_visible" value="0">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" value="1" @checked(old('is_visible', $member->is_visible))>
                    <label class="form-check-label" for="is_visible">Afficher ce membre sur le site public</label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('board.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> {{ $editing ? 'Enregistrer' : 'Ajouter au bureau' }}</span></button>
        </div>
    </form>
@endsection

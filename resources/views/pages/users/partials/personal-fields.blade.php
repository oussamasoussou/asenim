{{--
    Champs « informations personnelles » partagés.
    Variables : $subject (User|null), $withRole (bool), $withAvatar (bool)
--}}
@php
    $subject = $subject ?? null;
    $withRole = $withRole ?? false;
    $withAvatar = $withAvatar ?? true;
    $currentRole = old('role', $subject->role ?? 'membre');
    $currentType = old('type', $subject->type ?? 'permanent');
@endphp

@if ($withAvatar)
    <div class="form-section">
        <div>
            <h2 class="form-section-title">Photo de profil</h2>
            <p class="form-section-text">Facultative. JPG ou PNG, 2 Mo maximum.</p>
        </div>
        <div>
            <div class="avatar-uploader">
                @if ($subject && $subject->image)
                    <img src="{{ asset('storage/' . $subject->image) }}" alt="" class="avatar-preview" id="avatarPreview">
                @else
                    <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 80'><rect width='80' height='80' fill='%23f1f5f9'/><circle cx='40' cy='31' r='14' fill='%23cbd5e1'/><path d='M14 72c3-14 14-22 26-22s23 8 26 22' fill='%23cbd5e1'/></svg>"
                        alt="" class="avatar-preview" id="avatarPreview">
                @endif
                <div>
                    <div class="d-flex gap-2 flex-wrap">
                        <label for="image" class="btn btn-secondary mb-0">
                            <i class="bx bx-upload"></i>{{ $subject && $subject->image ? 'Changer la photo' : 'Importer une photo' }}
                        </label>
                        <button type="button" class="btn btn-ghost" data-avatar-reset="image">Annuler</button>
                    </div>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg" class="d-none" data-avatar-input="#avatarPreview">
                    @error('image')
                        <div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endif

<div class="form-section">
    <div>
        <h2 class="form-section-title">Identité & contact</h2>
        <p class="form-section-text">Ces informations sont visibles par les autres membres.</p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <x-field name="first_name" label="Prénom" :value="$subject->first_name ?? null" required autocomplete="given-name" placeholder="Jean" />
        </div>
        <div class="col-md-6">
            <x-field name="last_name" label="Nom" :value="$subject->last_name ?? null" required autocomplete="family-name" placeholder="Dupont" />
        </div>
        <div class="col-md-6">
            <x-field name="email" type="email" label="Adresse e-mail" icon="bx-envelope" :value="$subject->email ?? null" required autocomplete="email" placeholder="jean.dupont@exemple.com" />
        </div>
        <div class="col-md-6">
            <x-field name="phone" type="tel" label="Téléphone" icon="bx-phone" :value="$subject->phone ?? null" required autocomplete="tel" placeholder="06 12 34 56 78" />
        </div>
    </div>
</div>

<div class="form-section">
    <div>
        <h2 class="form-section-title">{{ $withRole ? 'Accès & statut' : 'Statut' }}</h2>
        <p class="form-section-text">
            {{ $withRole ? 'Le rôle détermine les droits d’administration ; le statut, les documents accessibles.' : 'Votre statut détermine les documents auxquels vous avez accès.' }}
        </p>
    </div>
    <div>
        @if ($withRole)
            <fieldset class="mb-3">
                <legend class="form-label">Rôle<span class="req" aria-hidden="true">*</span></legend>
                <div class="segmented">
                    <input type="radio" name="role" id="role-membre" value="membre" @checked($currentRole === 'membre')>
                    <label for="role-membre"><i class="bx bx-user"></i><span><strong>Membre</strong><small>Accès standard à l’espace</small></span></label>
                    <input type="radio" name="role" id="role-admin" value="admin" @checked($currentRole === 'admin')>
                    <label for="role-admin"><i class="bx bx-shield-quarter"></i><span><strong>Administrateur</strong><small>Gère les utilisateurs</small></span></label>
                </div>
                @error('role')<div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>@enderror
            </fieldset>
        @endif

        <fieldset class="mb-0">
            <legend class="form-label">Statut du membre<span class="req" aria-hidden="true">*</span></legend>
            <div class="segmented">
                <input type="radio" name="type" id="type-permanent" value="permanent" @checked($currentType !== 'non_permanent')>
                <label for="type-permanent"><i class="bx bx-badge-check"></i><span><strong>Permanent</strong><small>Membre du GDI</small></span></label>
                <input type="radio" name="type" id="type-non-permanent" value="non_permanent" @checked($currentType === 'non_permanent')>
                <label for="type-non-permanent"><i class="bx bx-time"></i><span><strong>Non permanent</strong><small>Contrat ou mission temporaire</small></span></label>
            </div>
            @error('type')<div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>@enderror
        </fieldset>
    </div>
</div>

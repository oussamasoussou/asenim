{{-- Choix de la visibilité d'un document. Variable : $current --}}
<fieldset class="mb-0">
    <legend class="form-label">Visible par<span class="req" aria-hidden="true">*</span></legend>
    <div class="segmented">
        <input type="radio" name="member_type" id="mt-all" value="all_members" @checked($current === 'all_members' || !$current)>
        <label for="mt-all"><i class="bx bx-group"></i><span><strong>Tous les membres</strong><small>Toute personne connectée</small></span></label>
        <input type="radio" name="member_type" id="mt-permanent" value="permanent" @checked($current === 'permanent')>
        <label for="mt-permanent"><i class="bx bx-badge-check"></i><span><strong>Permanents (GDI)</strong><small>Réservé aux membres permanents</small></span></label>
        <input type="radio" name="member_type" id="mt-non-permanent" value="non_permanent" @checked($current === 'non_permanent')>
        <label for="mt-non-permanent"><i class="bx bx-time"></i><span><strong>Non permanents</strong><small>Réservé aux non-permanents</small></span></label>
    </div>
    @error('member_type')<div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>@enderror
</fieldset>

{{-- Champs « biographie & activités » partagés. Variable : $subject (User) --}}
<div class="form-section">
    <div>
        <h2 class="form-section-title">Biographie</h2>
        <p class="form-section-text">Présentez votre parcours en quelques lignes.</p>
    </div>
    <div>
        <x-textarea-field name="biography" label="Biographie" :value="$subject->biography" rows="6" rich
            placeholder="Parcours, domaines d’expertise, centres d’intérêt…" />
    </div>
</div>

<div class="form-section">
    <div>
        <h2 class="form-section-title">Activités</h2>
        <p class="form-section-text">Projets en cours, enseignements, responsabilités…</p>
    </div>
    <div>
        <x-textarea-field name="activities" label="Activités" :value="$subject->activities" rows="6" rich
            placeholder="Projets, enseignements, responsabilités… (utilisez une liste à puces)" />
    </div>
</div>

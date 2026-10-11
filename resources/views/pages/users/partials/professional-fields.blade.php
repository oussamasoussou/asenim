{{-- Champs « parcours professionnel » partagés. Variable : $subject (User) --}}
@php
    $grades = ['1' => 'Grade 1', '2' => 'Grade 2', '3' => 'Grade 3'];
@endphp

<div class="form-section">
    <div>
        <h2 class="form-section-title">Affiliation</h2>
        <p class="form-section-text">Votre établissement de rattachement et votre fonction actuelle.</p>
    </div>
    <div class="row">
        <div class="col-12">
            <x-field name="institution" label="Institution" icon="bx-buildings" :value="$subject->institution" placeholder="Université, laboratoire, entreprise…" autocomplete="organization" />
        </div>
        <div class="col-md-6">
            <x-field name="function" label="Fonction" :value="$subject->function" placeholder="Ex. Chercheur, Doctorant, Ingénieur" autocomplete="organization-title" />
        </div>
        <div class="col-md-6">
            <x-select-field name="grade" label="Grade" :options="$grades" :value="$subject->grade" placeholder="Sélectionner un grade" />
        </div>
    </div>
</div>

<div class="form-section">
    <div>
        <h2 class="form-section-title">Identifiant chercheur</h2>
        <p class="form-section-text">Facultatif. Facilite le référencement de vos publications.</p>
    </div>
    <div>
        <x-field name="orcid" label="ORCID" icon="bx-id-card" :value="$subject->orcid" placeholder="0000-0000-0000-0000" hint="Identifiant à 16 chiffres disponible sur orcid.org." />
    </div>
</div>

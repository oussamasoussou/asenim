@extends('layouts.app')

@section('title', 'Ajouter un document')

@section('content')
    <x-page-header title="Ajouter un document" :back="route('documents.non_archived')"
        subtitle="Le fichier sera immédiatement disponible pour les membres concernés." />

    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Fichier</h2>
                <p class="form-section-text">PDF ou image (JPG, PNG), 10 Mo maximum.</p>
            </div>
            <div>
                <x-dropzone name="file" accept="application/pdf,image/png,image/jpeg" hint="PDF, JPG ou PNG · 10 Mo max." required />
            </div>
        </div>

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Informations</h2>
                <p class="form-section-text">Un titre clair facilite la recherche.</p>
            </div>
            <div>
                <x-field name="file_name" label="Titre du document" required placeholder="Ex. Rapport annuel 2025" maxlength="255" />
                @include('pages.document.partials.audience-fields', ['current' => old('member_type', 'all_members')])
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('documents.non_archived') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-upload"></i> Publier le document</span></button>
        </div>
    </form>
@endsection

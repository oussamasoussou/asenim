@extends('layouts.app')

@section('title', 'Modifier le document')

@section('content')
    <x-page-header title="Modifier le document" :back="route('documents.non_archived')" :subtitle="$documents->file_name">
        <a href="{{ asset('storage/' . $documents->file_path) }}" target="_blank" rel="noopener" class="btn btn-secondary"><i class="bx bx-show"></i>Ouvrir le fichier</a>
    </x-page-header>

    <form method="POST" action="{{ route('documents.update', $documents->id) }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @method('PUT')

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Informations</h2>
                <p class="form-section-text">Titre affiché et visibilité du document.</p>
            </div>
            <div>
                <x-field name="file_name" label="Titre du document" :value="$documents->file_name" required maxlength="255" />
                @include('pages.document.partials.audience-fields', ['current' => old('member_type', $documents->member_type)])
            </div>
        </div>

        <div class="form-section">
            <div>
                <h2 class="form-section-title">Remplacer le fichier</h2>
                <p class="form-section-text">Facultatif. Laissez vide pour conserver le fichier actuel.</p>
            </div>
            <div>
                <div class="doc-tile mb-3">
                    <x-file-icon :path="$documents->file_path" />
                    <span class="min-w-0">
                        <span class="doc-tile-name d-block">Fichier actuel</span>
                        <span class="doc-tile-meta">Ajouté le {{ $documents->created_at?->translatedFormat('j F Y') }}</span>
                    </span>
                </div>
                <x-dropzone name="file" accept="application/pdf,image/png,image/jpeg" hint="PDF, JPG ou PNG · 10 Mo max." />
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('documents.non_archived') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Enregistrer</span></button>
        </div>
    </form>
@endsection

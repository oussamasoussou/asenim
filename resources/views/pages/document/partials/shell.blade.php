{{-- Gabarit commun des listes de documents. Variable : $archived (bool) + variables du tableau --}}
<x-page-header title="Documents" subtitle="Consultez, téléchargez et partagez les ressources de la plateforme.">
    <a href="{{ route('store-document') }}" class="btn btn-primary"><i class="bx bx-upload"></i>Ajouter un document</a>
</x-page-header>

<div class="card table-card">
    <div class="card-header">
        <nav class="segment-switch" aria-label="Filtrer les documents">
            <a href="{{ route('documents.non_archived') }}" class="{{ $archived ? '' : 'active' }}" @unless ($archived) aria-current="page" @endunless><i class="bx bx-folder"></i>Actifs</a>
            <a href="{{ route('documents.archived') }}" class="{{ $archived ? 'active' : '' }}" @if ($archived) aria-current="page" @endif><i class="bx bx-archive"></i>Archivés</a>
        </nav>
        <form action="{{ $archived ? route('documents.archived') : route('documents.non_archived') }}" method="GET" role="search"
            data-live-search data-target="#documents-list" class="search-field">
            <i class="bx bx-search" aria-hidden="true"></i>
            <label for="search" class="sr-only">Rechercher un document</label>
            <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                placeholder="Nom du document ou auteur…" autocomplete="off">
            <span class="search-spinner" aria-hidden="true"></span>
        </form>
    </div>

    <div id="documents-list" data-list-region>
        @include('pages.document.partials.table')
    </div>
</div>

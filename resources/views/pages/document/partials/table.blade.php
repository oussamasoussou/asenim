{{--
    Tableau des documents (actifs ou archivés).
    Variables : $documents, $archived (bool), $userConnected, $page, $totalPages, $perPage, $totalDocuments
--}}
<div class="table-responsive">
    <table class="table table-hover table-stack">
        <thead>
            <tr>
                <th scope="col"><x-sort-link column="file_name" label="Document" default="file_name" /></th>
                <th scope="col"><x-sort-link column="member_type" label="Visibilité" /></th>
                <th scope="col">Ajouté par</th>
                <th scope="col">
                    @if ($archived)
                        <x-sort-link column="deleted_at" label="Archivé le" />
                    @else
                        <x-sort-link column="created_at" label="Date d’ajout" />
                    @endif
                </th>
                <th scope="col" class="col-actions"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $document)
                @php
                    $canManage = $userConnected->isAdmin() || (int) $document->user_id === (int) $userConnected->id;
                    $ext = strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION));
                @endphp
                <tr>
                    <td class="cell-primary">
                        <div class="user-cell">
                            <x-file-icon :path="$document->file_path" />
                            <div class="min-w-0">
                                @if ($archived)
                                    <div class="user-cell-name text-truncate">{{ $document->file_name }}</div>
                                @else
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" rel="noopener" class="user-cell-name d-block text-truncate">{{ $document->file_name }}</a>
                                @endif
                                <div class="user-cell-meta">{{ $ext ?: 'Fichier' }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="Visibilité"><x-audience-badge :type="$document->member_type" /></td>
                    <td data-label="Ajouté par">
                        @if ($document->user)
                            <div class="user-cell">
                                <x-avatar :user="$document->user" size="xs" />
                                <span class="text-truncate">{{ $document->user->first_name }} {{ $document->user->last_name }}</span>
                            </div>
                        @else
                            <span class="text-muted">Compte archivé</span>
                        @endif
                    </td>
                    <td data-label="{{ $archived ? 'Archivé le' : 'Date d’ajout' }}" class="tabular">
                        {{ ($archived ? $document->deleted_at : $document->created_at)?->translatedFormat('j M Y') }}
                    </td>
                    <td class="col-actions">
                        <div class="row-actions">
                            @if ($archived)
                                @if ($canManage)
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-confirm="« {{ $document->file_name }} » sera de nouveau visible dans la liste des documents."
                                        data-title="Restaurer ce document ?"
                                        data-action="{{ route('documents.restore', $document->id) }}" data-method="POST"
                                        data-variant="success" data-icon="bx-undo" data-confirm-label="Restaurer">
                                        <i class="bx bx-undo"></i>Restaurer
                                    </button>
                                @endif
                            @else
                                <a href="{{ asset('storage/' . $document->file_path) }}" class="icon-action is-primary" download
                                    title="Télécharger" aria-label="Télécharger {{ $document->file_name }}"><i class="bx bx-download"></i></a>
                                @if ($canManage)
                                    <a href="{{ route('documents.edit', $document->id) }}" class="icon-action"
                                        title="Modifier" aria-label="Modifier {{ $document->file_name }}"><i class="bx bx-edit-alt"></i></a>
                                    <button type="button" class="icon-action is-danger" title="Archiver" aria-label="Archiver {{ $document->file_name }}"
                                        data-confirm="« {{ $document->file_name }} » ne sera plus visible par les membres. Vous pourrez le restaurer depuis les archives."
                                        data-title="Archiver ce document ?"
                                        data-action="{{ route('documents.delete', $document->id) }}" data-method="DELETE"
                                        data-icon="bx-archive-in" data-confirm-label="Archiver">
                                        <i class="bx bx-archive-in"></i>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="cell-empty">
                        @if (request('search'))
                            <x-empty-state icon="bx-search" title="Aucun résultat"
                                text="Aucun document ne correspond à « {{ request('search') }} »." />
                        @elseif ($archived)
                            <x-empty-state icon="bx-archive" title="Aucun document archivé"
                                text="Les documents archivés apparaîtront ici et pourront être restaurés." />
                        @else
                            <x-empty-state icon="bx-folder-open" title="Aucun document pour le moment"
                                text="Partagez un premier fichier avec les membres de la plateforme.">
                                <a href="{{ route('store-document') }}" class="btn btn-primary btn-sm"><i class="bx bx-upload"></i>Ajouter un document</a>
                            </x-empty-state>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-pagination :page="$page" :total-pages="$totalPages" :per-page="$perPage" :total="$totalDocuments" />

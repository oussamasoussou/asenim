{{--
    Liste paginée des actualités / événements (actifs ou archivés), en onglets.
    Variables : $items, $tab, $newsCount, $eventsCount, $total, $page, $totalPages, $perPage, $archived (bool), $userConnected
--}}
@php
    $tabUrl = fn ($t) => request()->fullUrlWithQuery(['tab' => $t, 'page' => null]);
@endphp

<x-page-header title="Actualités & événements" subtitle="Informez les membres des nouveautés et des prochains rendez-vous.">
    <a href="{{ route('store-news') }}" class="btn btn-primary"><i class="bx bx-plus"></i>Nouvelle publication</a>
</x-page-header>

<div class="card table-card">
    <div class="card-header">
        <nav class="segment-switch" aria-label="Filtrer les publications">
            <a href="{{ route('news.index', ['tab' => $tab]) }}" class="{{ $archived ? '' : 'active' }}"><i class="bx bx-news"></i>Publiées</a>
            <a href="{{ route('news.archived', ['tab' => $tab]) }}" class="{{ $archived ? 'active' : '' }}"><i class="bx bx-archive"></i>Archivées</a>
        </nav>
        <form action="{{ $archived ? route('news.archived') : route('news.index') }}" method="GET" role="search"
            data-live-search data-target="#news-list" class="search-field">
            <i class="bx bx-search" aria-hidden="true"></i>
            <label for="search" class="sr-only">Rechercher une publication</label>
            <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                placeholder="Titre, contenu ou auteur…" autocomplete="off">
            <span class="search-spinner" aria-hidden="true"></span>
        </form>
    </div>

    <div id="news-list" data-list-region>
        <div class="px-3 px-md-4">
            <nav class="tabs-underline mb-0" aria-label="Type de publication">
                <a href="{{ $tabUrl('news') }}" data-list-link class="{{ $tab === 'news' ? 'active' : '' }}">
                    <i class="bx bx-news"></i>Actualités <span class="tab-count">{{ $newsCount }}</span>
                </a>
                <a href="{{ $tabUrl('events') }}" data-list-link class="{{ $tab === 'events' ? 'active' : '' }}">
                    <i class="bx bx-calendar-event"></i>Événements <span class="tab-count">{{ $eventsCount }}</span>
                </a>
            </nav>
        </div>

        <ul class="list-plain">
            @forelse ($items as $item)
                @php
                    $date = \Carbon\Carbon::parse($item->date);
                    $canManage = $userConnected->isAdmin() || (int) $item->user_id === (int) $userConnected->id;
                    $author = $item->user ? $item->user->first_name . ' ' . $item->user->last_name : '';
                @endphp
                <li class="news-item">
                    @if ($tab === 'events')
                        <span class="date-chip {{ $date->isPast() ? 'is-past' : '' }}" aria-hidden="true">
                            <span class="date-chip-month">{{ $date->translatedFormat('M') }}</span>
                            <span class="date-chip-day">{{ $date->format('d') }}</span>
                        </span>
                    @elseif ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="" class="news-thumb" loading="lazy">
                    @else
                        <span class="news-thumb"><i class="bx bx-news"></i></span>
                    @endif

                    <div class="min-w-0 flex-grow-1">
                        <h3 class="news-item-title">
                            <a href="#" data-open-article="{{ $item->id }}">{{ $item->title }}</a>
                            @if ($tab === 'events' && $date->isPast())
                                <span class="badge badge-neutral ms-1">Passé</span>
                            @endif
                        </h3>
                        <div class="news-item-meta">
                            @if ($tab === 'events')
                                <span><i class="bx bx-time-five"></i>{{ $date->translatedFormat('l j F Y · H\hi') }}</span>
                            @else
                                <span><i class="bx bx-calendar"></i>{{ $item->created_at->translatedFormat('j M Y') }}</span>
                            @endif
                            @if ($item->user)
                                <span><i class="bx bx-user"></i>{{ $author }}</span>
                            @endif
                            @if ($archived && $item->deleted_at)
                                <span><i class="bx bx-archive"></i>Archivé le {{ $item->deleted_at->translatedFormat('j M Y') }}</span>
                            @endif
                        </div>
                        @if ($item->content)
                            <p class="news-item-excerpt text-truncate-2">{{ \App\Support\RichText::excerpt($item->content, 220) }}</p>
                        @endif
                    </div>

                    <div class="row-actions align-self-center">
                        <button type="button" class="icon-action is-primary" data-open-article="{{ $item->id }}" title="Lire" aria-label="Lire {{ $item->title }}">
                            <i class="bx bx-show"></i>
                        </button>
                        @if ($canManage)
                            @if ($archived)
                                <button type="button" class="icon-action is-success" title="Restaurer" aria-label="Restaurer {{ $item->title }}"
                                    data-confirm="« {{ $item->title }} » sera de nouveau visible par les membres."
                                    data-title="Restaurer cette publication ?"
                                    data-action="{{ route('news.restore', $item->id) }}" data-method="POST"
                                    data-variant="success" data-icon="bx-undo" data-confirm-label="Restaurer">
                                    <i class="bx bx-undo"></i>
                                </button>
                            @else
                                <a href="{{ route('news.edit', $item->id) }}" class="icon-action" title="Modifier" aria-label="Modifier {{ $item->title }}">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <button type="button" class="icon-action is-danger" title="Archiver" aria-label="Archiver {{ $item->title }}"
                                    data-confirm="« {{ $item->title }} » ne sera plus visible par les membres. Vous pourrez la restaurer depuis les archives."
                                    data-title="Archiver cette publication ?"
                                    data-action="{{ route('news.delete', $item->id) }}" data-method="DELETE"
                                    data-icon="bx-archive-in" data-confirm-label="Archiver">
                                    <i class="bx bx-archive-in"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                    @include('partials.article-template', ['item' => $item])
                </li>
            @empty
                <li>
                    @if (request('search'))
                        <x-empty-state icon="bx-search" title="Aucun résultat"
                            text="Aucune publication ne correspond à « {{ request('search') }} »." />
                    @elseif ($archived)
                        <x-empty-state icon="bx-archive" title="Aucune publication archivée"
                            text="Les publications archivées apparaîtront ici et pourront être restaurées." />
                    @elseif ($tab === 'events')
                        <x-empty-state icon="bx-calendar" title="Aucun événement"
                            text="Planifiez un séminaire, une réunion ou une soutenance.">
                            <a href="{{ route('store-news', ['type' => 'event']) }}" class="btn btn-primary btn-sm"><i class="bx bx-calendar-plus"></i>Créer un événement</a>
                        </x-empty-state>
                    @else
                        <x-empty-state icon="bx-news" title="Aucune actualité"
                            text="Partagez une information avec l’ensemble des membres.">
                            <a href="{{ route('store-news') }}" class="btn btn-primary btn-sm"><i class="bx bx-plus"></i>Publier une actualité</a>
                        </x-empty-state>
                    @endif
                </li>
            @endforelse
        </ul>

        <x-pagination :page="$page" :total-pages="$totalPages" :per-page="$perPage" :total="$total" />
    </div>
</div>

@include('partials.article-modal')

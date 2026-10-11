@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    @php
        $hour = (int) now()->format('H');
        $greeting = $hour < 18 ? 'Bonjour' : 'Bonsoir';
    @endphp

    <x-page-header :title="$greeting . ', ' . \Illuminate\Support\Str::title(mb_strtolower($userConnected->first_name))"
        subtitle="Voici les dernières informations de votre espace de travail.">
        <a href="{{ route('store-document') }}" class="btn btn-secondary"><i class="bx bx-upload"></i>Ajouter un document</a>
        <a href="{{ route('store-news') }}" class="btn btn-primary"><i class="bx bx-plus"></i>Publier</a>
    </x-page-header>

    {{-- Indicateurs --}}
    <div class="row g-3 mb-4">
        @if (!is_null($stats['users']))
            <div class="col-6 col-xl-3">
                <a href="{{ route('users.index') }}" class="card card-link-hover stat-card text-reset">
                    <span class="stat-icon badge-primary"><i class="bx bx-group"></i></span>
                    <div>
                        <p class="stat-label">Utilisateurs</p>
                        <p class="stat-value">{{ $stats['users'] }}</p>
                    </div>
                </a>
            </div>
        @else
            <div class="col-6 col-xl-3">
                <a href="{{ route('chat.index') }}" class="card card-link-hover stat-card text-reset">
                    <span class="stat-icon badge-primary"><i class="bx bx-wifi"></i></span>
                    <div>
                        <p class="stat-label">Membres en ligne</p>
                        <p class="stat-value">{{ $stats['online'] }}</p>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-6 col-xl-3">
            <a href="{{ route('documents.non_archived') }}" class="card card-link-hover stat-card text-reset">
                <span class="stat-icon badge-violet"><i class="bx bx-folder"></i></span>
                <div>
                    <p class="stat-label">Documents</p>
                    <p class="stat-value">{{ $stats['documents'] }}</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a href="{{ route('news.index') }}" class="card card-link-hover stat-card text-reset">
                <span class="stat-icon badge-info"><i class="bx bx-news"></i></span>
                <div>
                    <p class="stat-label">Actualités</p>
                    <p class="stat-value">{{ $stats['news'] }}</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a href="{{ route('news.index', ['tab' => 'events']) }}" class="card card-link-hover stat-card text-reset">
                <span class="stat-icon badge-success"><i class="bx bx-calendar-event"></i></span>
                <div>
                    <p class="stat-label">Événements à venir</p>
                    <p class="stat-value">{{ $stats['events'] }}</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Actualités récentes --}}
        <div class="col-lg-7">
            <section class="card h-100" aria-labelledby="news-title">
                <div class="card-header">
                    <div>
                        <h2 class="card-title" id="news-title">Actualités récentes</h2>
                        <p class="card-subtitle">Les dernières publications de l’équipe</p>
                    </div>
                    <a href="{{ route('news.index') }}" class="btn btn-ghost btn-sm">Tout voir <i class="bx bx-right-arrow-alt"></i></a>
                </div>
                @forelse ($news as $item)
                    <article class="news-item">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="" class="news-thumb" loading="lazy">
                        @else
                            <span class="news-thumb"><i class="bx bx-news"></i></span>
                        @endif
                        <div class="min-w-0 flex-grow-1">
                            <h3 class="news-item-title text-truncate-2">
                                <a href="#" data-open-article="{{ $item->id }}">{{ $item->title }}</a>
                            </h3>
                            <div class="news-item-meta">
                                @if ($item->user)
                                    <span><i class="bx bx-user"></i>{{ $item->user->first_name }} {{ $item->user->last_name }}</span>
                                @endif
                                <span><i class="bx bx-time-five"></i>{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @include('partials.article-template', ['item' => $item])
                    </article>
                @empty
                    <x-empty-state icon="bx-news" title="Aucune actualité pour le moment"
                        text="Les publications de l’équipe apparaîtront ici.">
                        <a href="{{ route('store-news') }}" class="btn btn-secondary btn-sm"><i class="bx bx-plus"></i>Publier une actualité</a>
                    </x-empty-state>
                @endforelse
            </section>
        </div>

        {{-- Événements à venir --}}
        <div class="col-lg-5">
            <section class="card h-100" aria-labelledby="events-title">
                <div class="card-header">
                    <div>
                        <h2 class="card-title" id="events-title">Événements à venir</h2>
                        <p class="card-subtitle">Prochaines dates à retenir</p>
                    </div>
                    <a href="{{ route('news.index', ['tab' => 'events']) }}" class="btn btn-ghost btn-sm">Agenda <i class="bx bx-right-arrow-alt"></i></a>
                </div>
                @forelse ($events as $event)
                    @php $date = \Carbon\Carbon::parse($event->date); @endphp
                    <article class="news-item">
                        <span class="date-chip" aria-hidden="true">
                            <span class="date-chip-month">{{ $date->translatedFormat('M') }}</span>
                            <span class="date-chip-day">{{ $date->format('d') }}</span>
                        </span>
                        <div class="min-w-0">
                            <h3 class="news-item-title text-truncate-2">
                                <a href="#" data-open-article="{{ $event->id }}">{{ $event->title }}</a>
                            </h3>
                            <div class="news-item-meta">
                                <span><i class="bx bx-time-five"></i>{{ $date->translatedFormat('l j F · H\hi') }}</span>
                            </div>
                        </div>
                        @include('partials.article-template', ['item' => $event])
                    </article>
                @empty
                    <x-empty-state icon="bx-calendar" title="Aucun événement programmé"
                        text="Planifiez le prochain rendez-vous de l’équipe." compact>
                        <a href="{{ route('store-news') }}" class="btn btn-secondary btn-sm"><i class="bx bx-calendar-plus"></i>Créer un événement</a>
                    </x-empty-state>
                @endforelse
            </section>
        </div>

        {{-- Documents récents --}}
        <div class="col-12">
            <section class="card" aria-labelledby="docs-title">
                <div class="card-header">
                    <div>
                        <h2 class="card-title" id="docs-title">Documents récents</h2>
                        <p class="card-subtitle">Derniers fichiers partagés sur la plateforme</p>
                    </div>
                    <a href="{{ route('documents.non_archived') }}" class="btn btn-ghost btn-sm">Tous les documents <i class="bx bx-right-arrow-alt"></i></a>
                </div>
                <div class="card-body">
                    @if ($documents->isEmpty())
                        <x-empty-state icon="bx-folder-open" title="Aucun document partagé"
                            text="Ajoutez un premier document pour le rendre accessible aux membres." compact>
                            <a href="{{ route('store-document') }}" class="btn btn-secondary btn-sm"><i class="bx bx-upload"></i>Ajouter un document</a>
                        </x-empty-state>
                    @else
                        <div class="row g-3">
                            @foreach ($documents as $document)
                                <div class="col-sm-6 col-xl-4">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" rel="noopener" class="doc-tile">
                                        <x-file-icon :path="$document->file_path" />
                                        <span class="min-w-0 flex-grow-1">
                                            <span class="doc-tile-name d-block">{{ $document->file_name }}</span>
                                            <span class="doc-tile-meta">
                                                {{ $document->created_at->translatedFormat('j M Y') }}
                                                @if ($document->user) · {{ $document->user->first_name }} {{ $document->user->last_name }} @endif
                                            </span>
                                        </span>
                                        <i class="bx bx-download text-muted" aria-label="Télécharger"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    @include('partials.article-modal')
@endsection

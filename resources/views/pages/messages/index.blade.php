@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <x-page-header title="Messages" subtitle="Messages envoyés depuis le formulaire « Contact » du site public." />

    <div class="card table-card">
        <div class="card-header">
            <nav class="segment-switch" aria-label="Filtrer les messages">
                <a href="{{ route('messages.index') }}" class="{{ $filter === 'all' ? 'active' : '' }}" @if ($filter === 'all') aria-current="page" @endif>
                    <i class="bx bx-envelope"></i>Tous
                </a>
                <a href="{{ route('messages.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'active' : '' }}" @if ($filter === 'unread') aria-current="page" @endif>
                    <i class="bx bx-envelope-open"></i>Non lus
                    @if ($unreadCount > 0)<span class="badge badge-danger ms-1">{{ $unreadCount }}</span>@endif
                </a>
            </nav>
            <form action="{{ route('messages.index') }}" method="GET" role="search" data-live-search data-target="#messages-list" class="search-field">
                <i class="bx bx-search" aria-hidden="true"></i>
                <label for="search" class="sr-only">Rechercher un message</label>
                @if ($filter === 'unread')<input type="hidden" name="filter" value="unread">@endif
                <input type="search" id="search" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom, e-mail, objet ou contenu…" autocomplete="off">
                <span class="search-spinner" aria-hidden="true"></span>
            </form>
        </div>

        <div id="messages-list" data-list-region>
            <ul class="list-plain">
                @forelse ($messages as $message)
                    <li class="news-item message-row {{ $message->isRead() ? '' : 'is-unread' }}">
                        <span class="ui-avatar tone-{{ crc32($message->email) % 6 }}" aria-hidden="true">
                            <span>{{ mb_strtoupper(mb_substr($message->name, 0, 1)) }}</span>
                        </span>
                        <div class="min-w-0 flex-grow-1">
                            <h3 class="news-item-title">
                                <a href="{{ route('messages.show', $message->id) }}">{{ $message->subject }}</a>
                                @unless ($message->isRead())<span class="badge badge-primary badge-dot ms-1">Nouveau</span>@endunless
                            </h3>
                            <div class="news-item-meta">
                                <span><i class="bx bx-user"></i>{{ $message->name }}</span>
                                <span><i class="bx bx-envelope"></i>{{ $message->email }}</span>
                                <span><i class="bx bx-time-five"></i>{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="news-item-excerpt text-truncate-2">{{ \Illuminate\Support\Str::limit($message->message, 220) }}</p>
                        </div>
                        <div class="row-actions align-self-center">
                            <a href="{{ route('messages.show', $message->id) }}" class="icon-action is-primary" title="Lire" aria-label="Lire le message de {{ $message->name }}"><i class="bx bx-show"></i></a>
                            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re : ' . $message->subject) }}" class="icon-action" title="Répondre par e-mail" aria-label="Répondre à {{ $message->name }}"><i class="bx bx-reply"></i></a>
                            <button type="button" class="icon-action is-danger" title="Supprimer" aria-label="Supprimer le message de {{ $message->name }}"
                                data-confirm="Le message « {{ $message->subject }} » de {{ $message->name }} sera définitivement supprimé."
                                data-title="Supprimer ce message ?"
                                data-action="{{ route('messages.destroy', $message->id) }}" data-method="DELETE"
                                data-icon="bx-trash" data-confirm-label="Supprimer">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </li>
                @empty
                    <li>
                        @if (request('search'))
                            <x-empty-state icon="bx-search" title="Aucun résultat" text="Aucun message ne correspond à « {{ request('search') }} »." />
                        @elseif ($filter === 'unread')
                            <x-empty-state icon="bx-check-double" title="Tout est lu" text="Vous avez lu tous les messages reçus." />
                        @else
                            <x-empty-state icon="bx-envelope" title="Aucun message"
                                text="Les messages envoyés depuis la page « Contact » du site public apparaîtront ici." />
                        @endif
                    </li>
                @endforelse
            </ul>

            <x-pagination :page="$page" :total-pages="$totalPages" :per-page="$perPage" :total="$total" />
        </div>
    </div>
@endsection

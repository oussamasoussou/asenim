@extends('layouts.app')

@section('title', $message->subject)

@section('content')
    <x-page-header :title="$message->subject" :back="route('messages.index')"
        :subtitle="'Reçu le ' . $message->created_at->translatedFormat('l j F Y à H\hi')">
        <form method="POST" action="{{ route('messages.unread', $message->id) }}">
            @csrf
            <button type="submit" class="btn btn-secondary"><span><i class="bx bx-envelope"></i> Marquer non lu</span></button>
        </form>
        <button type="button" class="btn btn-soft-danger"
            data-confirm="Le message « {{ $message->subject }} » de {{ $message->name }} sera définitivement supprimé."
            data-title="Supprimer ce message ?"
            data-action="{{ route('messages.destroy', $message->id) }}" data-method="DELETE"
            data-icon="bx-trash" data-confirm-label="Supprimer">
            <i class="bx bx-trash"></i>Supprimer
        </button>
        <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re : ' . $message->subject) }}" class="btn btn-primary">
            <i class="bx bx-reply"></i>Répondre par e-mail
        </a>
    </x-page-header>

    <div class="row g-4 align-items-start">
        <div class="col-xl-8">
            <article class="card">
                <div class="card-body">
                    <div class="article-body">{{ $message->message }}</div>
                </div>
            </article>
        </div>
        <div class="col-xl-4">
            <section class="card" aria-labelledby="sender-title">
                <div class="card-header">
                    <h2 class="card-title" id="sender-title">Expéditeur</h2>
                </div>
                <ul class="list-plain contact-list">
                    <li>
                        <span class="stat-icon badge-primary"><i class="bx bx-user"></i></span>
                        <div class="min-w-0">
                            <p class="contact-label">Nom</p>
                            <p class="contact-value">{{ $message->name }}</p>
                        </div>
                    </li>
                    <li>
                        <span class="stat-icon badge-violet"><i class="bx bx-envelope"></i></span>
                        <div class="min-w-0">
                            <p class="contact-label">E-mail</p>
                            <a href="mailto:{{ $message->email }}" class="contact-value d-block text-truncate">{{ $message->email }}</a>
                        </div>
                    </li>
                    <li>
                        <span class="stat-icon badge-success"><i class="bx bx-time-five"></i></span>
                        <div class="min-w-0">
                            <p class="contact-label">Reçu</p>
                            <p class="contact-value">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </div>
@endsection

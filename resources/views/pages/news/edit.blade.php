@extends('layouts.app')

@section('title', 'Modifier la publication')

@section('content')
    <x-page-header title="Modifier la publication" :back="route('news.index', ['tab' => $news->events_news === 'event' ? 'events' : 'news'])"
        :subtitle="$news->title" />

    <form method="POST" action="{{ route('news.update', $news->id) }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @method('PUT')
        @include('pages.news.partials.form-fields', ['item' => $news])

        <div class="form-actions">
            <a href="{{ route('news.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Enregistrer</span></button>
        </div>
    </form>
@endsection

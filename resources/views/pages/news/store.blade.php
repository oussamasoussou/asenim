@extends('layouts.app')

@section('title', 'Nouvelle publication')

@section('content')
    <x-page-header title="Nouvelle publication" :back="route('news.index')"
        subtitle="Publiez une actualité ou annoncez un événement à l’ensemble des membres." />

    <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data" class="card" novalidate>
        @csrf
        @include('pages.news.partials.form-fields', ['item' => null])

        <div class="form-actions">
            <a href="{{ route('news.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><span><i class="bx bx-send"></i> Publier</span></button>
        </div>
    </form>
@endsection

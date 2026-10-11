@extends('layouts.app')

@section('title', 'Actualités & événements')

@section('content')
    @include('pages.news.partials.list', ['archived' => false])
@endsection

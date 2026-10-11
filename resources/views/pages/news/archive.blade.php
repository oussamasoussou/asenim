@extends('layouts.app')

@section('title', 'Publications archivées')

@section('content')
    @include('pages.news.partials.list', ['archived' => true])
@endsection

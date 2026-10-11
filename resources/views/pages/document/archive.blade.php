@extends('layouts.app')

@section('title', 'Documents archivés')

@section('content')
    @include('pages.document.partials.shell', ['archived' => true])
@endsection

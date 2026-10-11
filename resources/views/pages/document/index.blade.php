@extends('layouts.app')

@section('title', 'Documents')

@section('content')
    @include('pages.document.partials.shell', ['archived' => false])
@endsection

@extends('layout')
@section('content')

<!-- Flash Message -->
@if(session('flash_message'))
<div class="alert alert-success">
    {{ session('flash_message') }}
</div>
@endif 

<div class="card" style="margin:20px;">
    <div class="card-header">Išteklio informacija</div>
    <div class="card-body">
        <h5 class="card-title">{{ $resources->name}} </h5>
        <p class="card-text">Tipas: {{ $resources->type}} </p>
        <p class="card-text">Aprašymas: {{ $resources->description}} </p>
        <p class="card-text">Apskritis: {{ $resources->county}} </p>
        <p class="card-text">Adresas: {{ $resources->address}} </p>
        <p class="card-text">Telefono numeris: {{ $resources->telephone}} </p>
        <p class="card-text">El. paštas: {{ $resources->email}} </p>
        <?php if ($resources->image && file_exists(public_path('images/' . $resources->image))) { ?>
            <p class="card-text">Paveikslėlis:</p>
            <div class="img-container pb-4">
                <img src="{{ asset('images/' . $resources->image) }}" alt="{{ $resources->name }}">
            </div>
        <?php } ?>
        <a href="{{ route('resources') }}" class="btn red-button">Grįžti į išteklių puslapį</a>
        @can('updateOrDelete', $resources)
            <a href="{{ route('resources.edit', $resources->id) }}" class="btn btn-success">Redaguoti</a>
        @endcan
    </div>
</div>
@stop

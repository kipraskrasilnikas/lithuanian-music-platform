@extends('layout')
@section('content')

<!-- Flash Message -->
@if(session('flash_message'))
<div class="alert alert-success">
    {{ session('flash_message') }}
</div>
@endif 

<div class="card" style="margin:20px;">
    <div class="card-header">Resurso informacija</div>
    <div class="card-body">
        <h5 class="card-title">Pavadinimas: {{ $resources->name}} </h5>
        <p class="card-text">Tipas: {{ $resources->type}} </p>
        <p class="card-text">Aprašymas: {{ $resources->description}} </p>
        <p class="card-text">Adresas: {{ $resources->address}} </p>
        <p class="card-text">Telefono numeris: {{ $resources->telephone}} </p>
        <p class="card-text">El. paštas: {{ $resources->email}} </p>
        <?php if (file_exists('images/' . $resources->image)) { ?>
            <p class="card-text">Paveikslėlis:</p>
            <div class="img-container">
                <img src="{{ asset('images/' . $resources->image) }}" alt="{{ $resources->name }}">
            </div>
        <?php } ?>
        <a href="{{ route('resource') }}" class="btn btn-primary">Grįžti į resursų puslapį</a>
        <a href="{{ route('resources.edit', $resources->id) }}" class="btn btn-success">Redaguoti dar kartą</a>
    </div>
</div>
@stop

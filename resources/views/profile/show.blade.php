@extends('layout')
@section('content')

<!-- Flash Message -->
@if(session('flash_message'))
<div class="alert alert-success">
    {{ session('flash_message') }}
</div>
@endif 

<div class="card" style="margin:20px;">
    <div class="card-header">Muzikanto informacija</div>
    <div class="card-body">
        <h5 class="card-title">Vardas: {{ $user->name}} </h5>

        <p class="card-text">Vietos:</p>
        @foreach ($user->locations->sortBy('county') as $location)
            <p class="card-text"> {{ (isset($location->county) ? ($location->county . ', ') : '') . (isset($location->city) ? $location->city  : '') . (isset($location->address) ? (', ' . $location->address)  : '') }} </p>
        @endforeach
        <br>

        <p class="card-text">Žanrai:</p>
        @foreach ($user->genres->sortBy('name') as $genre)
            <span class="position genre-filter" data-genre="{{ $genre->name }}">{{ $genre->name }}</span>
        @endforeach
        <br>

        <p class="card-text">Specializacijos:</p>
        @foreach ($user->specialties->sortBy('name') as $specialty)
            <p class="mb-2 position position-darker specialty-filter" data-specialty="{{ $specialty->name }}">{{ $specialty->name }}</p>
        @endforeach
        <br>

        <p class="card-text">Atliekamos muzikos nuotaikos:</p>
        @foreach ($user->artistMoods as $mood)
            @php
                $escapedMood = str_replace(['/', ' '], '_', $mood->mood);
            @endphp

            <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood->mood }}">{{ $mood->mood }}</p>
        @endforeach
        <br>

        <p class="card-text">Aprašymas: {{ $user->description}} </p>
        <?php if ($user->image && file_exists(public_path('images/' . $user->image))) { ?>
            <p class="card-text">Paveikslėlis:</p>
            <div class="img-container pb-4">
                <img src="{{ asset('images/' . $user->image) }}" alt="{{ $user->name }}">
            </div>
        <?php } ?>
        <a href="{{ route('search') }}" class="btn btn-primary">Grįžti į muzikantų paieškos puslapį</a>

        // cia dar reikes perdaryt
        @can('updateOrDelete', $resources)
            <a href="{{ route('resources.edit', $resources->id) }}" class="btn btn-success">Redaguoti</a>
        @endcan
    </div>
</div>
@stop

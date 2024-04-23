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
        <h3 class="card-title mb-3">{{ $user->name}} </h3>

        <p class="card-text"><strong>Vietos:</strong></p>
        @foreach ($user->locations->sortBy('county') as $location)
            <p class="card-text"> {{ (isset($location->county) ? ($location->county . ', ') : '') . (isset($location->city) ? $location->city  : '') . (isset($location->address) ? (', ' . $location->address)  : '') }} </p>
        @endforeach

        <p class="card-text"><strong>Žanrai:</strong></p>
        @foreach ($user->genres->sortBy('name') as $genre)
            <span class="position genre-filter" data-genre="{{ $genre->name }}"><strong>{{ $genre->name }}</strong></span>
        @endforeach

        <p class="card-text"><strong>Specializacijos:</strong></p>
        @foreach ($user->specialties->sortBy('name') as $specialty)
            <p class="position position-darker specialty-filter" data-specialty="{{ $specialty->name }}"><strong>{{ $specialty->name }}</strong></p>
        @endforeach

        <p class="card-text"><strong>Atliekamos muzikos nuotaikos:</strong></p>
        @foreach ($user->artistMoods as $mood)
            @php
                $escapedMood = str_replace(['/', ' '], '_', $mood->mood);
            @endphp

            <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood->mood }}"><strong>{{ $mood->mood }}</strong></p>
        @endforeach

        <p class="card-text"><strong>Aprašymas:</strong> {{ $user->description}} </p>
        <?php if ($user->image && file_exists(public_path('images/' . $user->image))) { ?>
            <p class="card-text"><strong>Paveikslėlis:</strong></p>
            <div class="img-container pb-4">
                <img src="{{ asset('images/' . $user->image) }}" alt="{{ $user->name }}">
            </div>
        <?php } ?>
        <a href="{{ route('search') }}" class="btn red-button">Grįžti į muzikantų paieškos puslapį</a>
    </div>
</div>
@stop

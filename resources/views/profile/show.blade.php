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
        <h3 class="card-title mb-3">{{ $user->name}}</h3>

        @if (count($user->locations) > 0) 
            <p class="card-text"><strong>Vietos:</strong></p>
            @foreach ($user->locations->sortBy('county') as $location)
                <p class="card-text"> {{ (isset($location->county) ? ($location->county . ', ') : '') . (isset($location->city) ? $location->city  : '') . (isset($location->address) ? (', ' . $location->address)  : '') }} </p>
            @endforeach
        @endif

        @if (count($user->genres) > 0) 
            <p class="card-text"><strong>Žanrai:</strong></p>
            @foreach ($user->genres->sortBy('name') as $genre)
                <span class="position genre-filter" data-genre="{{ $genre->name }}"><strong>{{ $genre->name }}</strong></span>
            @endforeach
        @endif

        @if (count($user->specialties) > 0) 
            <p class="card-text mt-2"><strong>Specializacijos:</strong></p>
            @foreach ($user->specialties->sortBy('name') as $specialty)
                <p class="position position-darker specialty-filter" data-specialty="{{ $specialty->name }}"><strong>{{ $specialty->name }}</strong></p>
            @endforeach
        @endif

        @if (count($user->moods) > 0)
            <p class="card-text mt-2"><strong>Atliekamos muzikos nuotaikos:</strong></p>
            @foreach ($user->moods as $mood)
                @php
                    $escapedMood = str_replace(['/', ' '], '_', $mood->mood);
                @endphp

                <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood->mood }}"><strong>{{ $mood->mood }}</strong></p>
            @endforeach
        @endif

        @if ($user->description)
            <p class="card-text mt-2"><strong>Aprašymas:</strong></p>
            <p class="card-text">{{ $user->description}} </p>
        @endif

        <?php if ($user->image && file_exists(public_path('images/' . $user->image))) { ?>
            <p class="card-text"><strong>Paveikslėlis:</strong></p>
            <div class="img-container pb-4">
                <img src="{{ asset('images/' . $user->image) }}" alt="{{ $user->name }}">
            </div>
        <?php } ?>

        @if (count($user->songs) > 0)
            <p class="card-text mt-2"><strong>Dainos:</strong></p>

            <div class="row row-cols-1 row-cols-md-5 g-4 mb-4" id="song-container">
                <!-- Initial 5 songs displayed -->
                @foreach ($user->songs->take(5) as $song)
                <div class="col song">
                    <div class="card h-100 w-300">
                        <h5 class="card-title" style="padding: 5px; text-align: center;"><a href="#">{{ $song->title }}</a></h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="100%" height="100%" class="embed-responsive-item" src="{{ $song->embedded_url }}" allowfullscreen></iframe>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 text-center">Nuotaikos</p>
                            @foreach ($song->moods as $mood)
                                @php
                                    $escapedMood = str_replace(['/', ' '], '_', $mood->mood);
                                @endphp
                                <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood->mood }}">{{ $mood->mood }}</p>
                            @endforeach

                            <p class="mb-0 mt-2 text-center">Žanrai</p>
                            @foreach (collect($song->genres)->sortBy('genre') as $genre)
                                <span class="position genre-filter" data-genre="{{ $genre->genre }}">{{ $genre->genre }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        <br>
        <a href="javascript:history.go(-1);" class="btn red-button">Grįžti</a>
    </div>
</div>
@stop

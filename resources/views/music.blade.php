@extends('layout')

@section('title', 'Music')

@section('content')
    <div class="container">
        <h1 class="display-4 text-center pb-5">Dainos</h1>
        <div class="row row-cols-1 row-cols-md-5 g-4" id="song-container">
            <!-- Initial 5 songs displayed -->
            @foreach ($songs->take(5) as $song)
                <div class="col song">
                    <div class="card h-100 w-300">
                        <h5 class="card-title" style="padding: 5px; text-align: center;">{{ $song->title }}</h5>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="100%" height="100%" class="embed-responsive-item" src="{{ $song->embedded_url }}" allowfullscreen></iframe>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Atlikėjas: <a href="{{ route('profile.show', $song->user->id)}}">{{ $song->user->name }}</a></h5>

                            <p class="mb-0 mt-3 text-center">Nuotaikos</p>
                            @foreach ($song->moods as $mood)
                                @php
                                    $escapedMood = str_replace(['/', ' '], '_', $mood);
                                @endphp
                                <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood }}">{{ $mood }}</p>
                            @endforeach

                            <p class="mb-0 mt-2 text-center">Žanrai</p>
                            @foreach (collect($song->genres)->sortBy('genre') as $genre)
                                <span class="position genre-filter" data-genre="{{ $genre }}">{{ $genre }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h1 class="display-4 text-center pb-5 mt-5">Atlikėjai</h1>
        <div class="row row-cols-1 row-cols-md-5 g-4" id="artist-container">
            <!-- Initial 5 songs displayed -->
            @foreach ($users->take(5) as $user)
                <div class="col song">
                    <div class="card h-100 w-300">
                        <h5 class="card-title text-center mt-3"><a href="{{ route('profile.show', $user->id)}}">{{ $user->name }}</a></h5>

                        <div class="img-container user-img-container center-img">
                            <?php if ($user->avatar && file_exists(public_path('images/' . $user->avatar))) { ?>
                                <img src="{{ asset('images/' . $user->avatar) }}" alt="{{ $user->name }}">
                            <?php } else { ?>
                                <img src="{{ asset('images/user_no_image.png') }}" alt="{{ $user->name }}">
                            <?php } ?>
                        </div>

                        <div class="card-body">

                            @if (count($user->artistMoods) > 0)
                                <p class="mb-0 mt-3 text-center">Nuotaikos</p>
                                @foreach ($user->artistMoods as $mood)
                                    @php
                                        $escapedMood = str_replace(['/', ' '], '_', $mood->mood);
                                    @endphp
                                    <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood->mood }}">{{ $mood->mood }}</p>
                                @endforeach
                            @endif

                            @if (count($user->genres) > 0)
                                <p class="mb-0 mt-3 text-center">Žanrai</p>
                                @foreach (collect($user->genres)->sortBy('genre') as $genre)
                                    <span class="position genre-filter" data-genre="{{ $genre->name }}">{{ $genre->name }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- See All button -->
        <div class="text-center mt-4">
            <button id="see-all-btn" class="btn btn-primary">See All</button>
        </div>
    </div>
@endsection
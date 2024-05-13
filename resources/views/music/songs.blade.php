@extends('layout')

@section('title', 'Dainų puslapis')

@section('content')
    <div class="container">
        <form action="{{ route('search.music.songs') }}" method="GET">
            <div class="filter-container">
                <div class="row">
                    <div class="col-md-4">
                        <label for="search">Paieška:</label>
                        <input type="text" class="form-control" name="search" value="{{ $search ?? ''}}" placeholder="Ieškoti...">
                    </div>
                    <div class="col-md-4">
                        <label for="genre-filter">Filtruoti pagal žanrą:</label>
                        <select class="form-select" name="genres[]" multiple>
                            @foreach (config('music_config.genres') as $genre)
                                <option value="{{ $genre }}" {{ in_array($genre, (isset($search_genres) ? $search_genres : []) ?? []) ? 'selected' : '' }}>{{ $genre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="mood-filter">Filtruoti pagal nuotaiką:</label>
                        <select class="form-select" name="moods[]" multiple>
                            @foreach (config('music_config.music_moods') as $mood_category => $mood_details)
                                <optgroup label="{{ $mood_category }}">
                                    @foreach ($mood_details['moods'] as $mood)
                                        <option value="{{ $mood }}" {{ in_array($mood, (isset($search_moods) ? $search_moods : []) ?? []) ? 'selected' : '' }}>{{ $mood }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4" style="width: 10%; margin-left: auto; margin-right: auto;">
                    <button type="submit" class="btn red-button">Ieškoti</button>
                </div>
            </div>
        </form>

        <h1 class="display-4 text-center pb-5">Dainos</h1>
        @if ($songs->isEmpty())
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            Pagal nurodytus paieškos kriterijus, dainų nerasta.
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-5 g-4" id="song-container">
                <!-- Initial 5 songs displayed -->
                @foreach ($songs as $song)
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
        @endif
        <!-- Pagination links -->
        <div class="row mt-5">
            <div class="col text-center">
                <div class="block-27">
                    <ul>
                        {{$songs->appends([
                            'search' => isset($search) ? $search : null,
                            'moods' => isset($search_moods) ? $search_moods : null,
                            'genres' => isset($search_genres) ? $search_genres : null,
                        ])->onEachSide(1)->links()}}                                                                            
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
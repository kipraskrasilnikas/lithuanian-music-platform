@extends('layout')

@section('title', 'Music')

@section('content')
    <div class="container">
        <form action="{{ route('search.music.artists') }}" method="GET">
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

        <h1 class="display-4 text-center pb-5 mt-5">Atlikėjai</h1>

        @if ($users->isEmpty())
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            Pagal nurodytus paieškos kriterijus, atlikėjų nerasta.
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-5 g-4" id="artist-container">
                @foreach ($users as $user)
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
                                    @foreach (collect($user->genres)->sortBy('name') as $genre)
                                        <span class="position genre-filter" data-genre="{{ $genre->name }}">{{ $genre->name }}</span>
                                    @endforeach
                                @endif
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
                        {{$users->appends([
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
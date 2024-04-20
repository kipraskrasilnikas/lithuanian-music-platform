@extends('layout')
@section('title', 'Paieškos puslapis')
@section('content')
    <body class="blog-page" data-bs-spy="scroll" data-bs-target="#navmenu">
        <div class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text align-items-end justify-content-start">
                    <div class="col-md-12 ftco-animate text-center mb-5">
                        <p class="breadcrumbs mb-0"><span class="mr-3"><a href="">Paieškos<i
                                        class="ion-ios-arrow-forward"></i></a></span> <span>puslapis</span></p>
                        <h1 class="mb-3 bread">Raskite savo kolaborantą</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="ftco-section ftco-candidates ftco-candidates-2 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 pr-lg-4">
                        <div class="row">
                            <div class="col-md-12">
                                @if ($users->isEmpty())
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    Pagal nurodytus paieškos kriterijus, muzikantų nerasta.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($users as $user)
                                        <div class="team d-md-flex p-4 bg-white">
                                            <div class="img" style="background-image: url(images/person_2.jpg);"></div>
                                            <div class="text pl-md-4">
                                                @php
                                                    $counties = $user->locations->pluck('county')->sort()->implode(', ');
                                                @endphp
                                                <span class="location mb-0">{{ $counties }}</span>
                                                <h2>{{ $user->name }}</h2>
                                                @foreach ($user->genres->sortBy('name') as $genre)
                                                    <span class="position genre-filter" data-genre="{{ $genre->name }}">{{ $genre->name }}</span>
                                                @endforeach
                                                <br>
                                                @foreach ($user->specialties->sortBy('name') as $specialty)
                                                    <p class="mb-2 position position-darker specialty-filter" data-specialty="{{ $specialty->name }}">{{ $specialty->name }}</p>
                                                @endforeach
                                                <br>
                                                @foreach ($user->artistMoods as $mood)
                                                    @php
                                                        $escapedMood = str_replace(['/', ' '], '_', $mood->mood);
                                                    @endphp
                                        
                                                    <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood->mood }}">{{ $mood->mood }}</p>
                                                @endforeach
                                                @if ($user->description)
                                                    <p class="description">{{ $user->description }}</p>
                                                @endif
                                                <p><a href="{{ route('user', $user->id) }}" class="btn red-button">Susisiekti</a></p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col text-center">
                                <div class="block-27">
                                    <ul>
                                        {{$users->appends([
                                            'search' => isset($search) ? $search : null,
                                            'specialties' => isset($search_specialties) ? $search_specialties : null,
                                            'genres' => isset($search_genres) ? $search_genres : null,
                                            'counties' => isset($search_counties) ? $search_counties : null
                                        ])->onEachSide(1)->links()}}                                                                            
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 sidebar">
                        <form action="/searchPost" method="GET" class="browse-form">
                            <div class="sidebar-box bg-white p-4 ftco-animate">
                                <h3 class="heading-sidebar">Ieškokite pagal raktažodį</h3>
                                <div class="form-group">
                                    <span class="icon icon-search"></span>
                                    <input type="text" value="{{ request('search') }}" name="search" class="form-control" placeholder="Search...">
                                </div>

                                <h3 class="heading-sidebar">Pagal specializaciją</h3>
                                @foreach (config('music_config.specialties') as $specialty)
                                    <label for="option-{{ $loop->iteration }}">
                                        <input type="checkbox" id="option-{{ $loop->iteration }}" name="specialties[]" value="{{ $specialty }}" {{ in_array($specialty, (isset($search_specialties) ? $search_specialties : [])) ? 'checked' : '' }}>
                                        {{ $specialty }}
                                    </label>
                                    <br>
                                @endforeach

                                <h3 class="heading-sidebar">Pagal žanrą</h3>
                                @foreach (config('music_config.genres') as $genre)
                                    <label for="option-{{ $loop->iteration }}">
                                        <input type="checkbox" id="option-{{ $loop->iteration }}" name="genres[]" value="{{ $genre }}" {{ in_array($genre, (isset($search_genres) ? $search_genres : [])) ? 'checked' : '' }}>
                                        {{ $genre }}
                                    </label>
                                    <br>
                                @endforeach

                                <h3 class="heading-sidebar">Pagal vietą</h3>
                                @foreach (config('music_config.counties') as $county)
                                    <label for="option-{{ $loop->iteration }}">
                                        <input type="checkbox" id="option-{{ $loop->iteration }}" name="counties[]" value="{{ $county }}" {{ in_array($county, (isset($search_counties) ? $search_counties : [])) ? 'checked' : '' }}>
                                        {{ $county }}
                                    </label>
                                    <br>
                                @endforeach

                                <h3 class="heading-sidebar">Pagal kuriamos muzikos nuotaiką</h3>
                                @foreach (config('music_config.music_moods') as $mood_category => $mood_details)
                                    <li style="list-style-type: none;">
                                        <div class="form-check" style="color: {{ $mood_details['color_hex'] }}">
                                            <input class="form-check-input mood-category" type="checkbox" id="mood-category-{{ $loop->iteration}}">{{ $mood_category }}</label>
                                        </div>
                                        <ul style="list-style-type: none;">
                                            @foreach ($mood_details['moods'] as $mood)
                                            <li style="list-style-type: none;">
                                                <div class="form-check" style="color: {{ $mood_details['color_hex'] }}">
                                                    <input class="form-check-input subOption" type="checkbox" id="mood-{{ $loop->iteration }}" name="moods[]" value="{{ $mood }}" {{ in_array($mood, (isset($search_moods) ? $search_moods : [])) ? 'checked' : '' }}>
                                                    {{ $mood }}
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach

                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn red-button">Ieškoti</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    </body>

    <script>
        // Check all nested checkboxes
        $('.mood-category').change( function(){
            $(this).parent().siblings().find(':checkbox').attr('checked', this.checked);
        }); 

        $(document).ready(function() {
            $('.mood-filter, .specialty-filter, .genre-filter').click(function() {
                // Clear all filters
                $('.mood-filter, .specialty-filter, .genre-filter').removeClass('active');
                // Set current filter as active
                $(this).addClass('active');
                
                var mood = $(this).data('mood');
                var specialty = $(this).data('specialty');
                var genre = $(this).data('genre');

                // Trigger form submission with the selected filter
                var form = $('.browse-form');
                form.find('input[name="moods[]"]').prop('checked', false); // Clear mood filters
                form.find('input[name="specialties[]"]').prop('checked', false); // Clear specialty filters
                form.find('input[name="genres[]"]').prop('checked', false); // Clear genre filters
                
                // Set the corresponding filter
                if (mood) {
                    form.find('input[name="moods[]"][value="' + mood + '"]').prop('checked', true);
                } else if (specialty) {
                    form.find('input[name="specialties[]"][value="' + specialty + '"]').prop('checked', true);
                } else if (genre) {
                    form.find('input[name="genres[]"][value="' + genre + '"]').prop('checked', true);
                }

                form.submit(); // Submit the form
            });
        });

    </script>

@endsection

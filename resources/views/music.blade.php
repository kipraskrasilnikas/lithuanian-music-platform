@extends('layout')

@section('title', 'Music')

@section('content')
    <div class="container">
        <h1 class="display-4 text-center pb-5">Dainos</h1>
        <div class="row row-cols-1 row-cols-md-5 g-4">
            @foreach ($songs as $key => $song)
                @if ($key < 5) <!-- Limiting to 5 songs -->
                    <div class="col">
                        <div class="card h-100 w-300">
                            <h5 class="card-title" style="padding: 5px; text-align: center;"><a href="#">{{ $song->title }}</a></h5>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe width="100%" height="100%" class="embed-responsive-item" src="{{ $song->embedded_url }}" allowfullscreen></iframe>
                            </div>
                            <div class="card-body">
                                {{-- // reiktu gal user name - dainos pavadinimas raut, arba galbut tik atlikejo varda cia vietoj title. nes kam tas title, jei embeddas yra. --}}
                                <h5 class="card-title">Atlikėjas: <a href="#">{{ $song->user->name }}</a></h5>

                                @foreach ($song->moods as $mood)
                                    @php
                                        $escapedMood = str_replace(['/', ' '], '_', $mood);
                                    @endphp
                        
                                    {{-- mood filter irgi reikes pritaikyt --}}
                                    <p class="mb-2 position mood-color-{{ $escapedMood }} mood-filter" data-mood="{{ $mood }}">{{ $mood }}</p>
                                @endforeach
                               
                                {{-- <h5 class="card-title"><a href="{{ route('song.show', $song->id) }}">{{ $song->name }}</a></h5> --}}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection

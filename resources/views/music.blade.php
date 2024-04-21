@extends('layout')

@section('title', 'Music')

@section('content')
    <div class="container">
        <h1 class="display-4 text-center">Dainos</h1>
        <div class="row row-cols-1 row-cols-md-5 g-4">
            @foreach ($songs as $song)
                <div class="col">
                    <div class="card h-100">
                        <!-- Embed YouTube video -->
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="{{ $song->song_url }}" allowfullscreen></iframe>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><a href="#">{{ $song->title }}</a></h5>
                            {{-- <h5 class="card-title"><a href="{{ route('song.show', $song->id) }}">{{ $song->name }}</a></h5> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

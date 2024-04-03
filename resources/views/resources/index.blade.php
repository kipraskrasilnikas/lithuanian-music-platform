@extends('layout')
@section('title', 'Paieškos puslapis')
@section('content')
    <body class="blog-page" data-bs-spy="scroll" data-bs-target="#navmenu">
        <!-- ======= Header ======= -->
        <header id="header" class="header sticky-top d-flex align-items-center">
            <div class="container-fluid d-flex align-items-center justify-content-between">

                <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                    <h1>Lietuvos muzikos platforma</h1>
                    <span>.</span>
                </a>

                <!-- Nav Menu -->
                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><a href="{{ route('home') }}#hero">Namų puslapis</a></li>
                        <li><a href="{{ route('search') }}">Muzikantų paieška</a></li>
                        <li><a href="{{ route('resource') }}" class="active">Ištekliai</a></li>

                        @auth
                            <li><a href="{{ route('profile') }}">Mano profilis</a></li>
                            <li><a href="{{ route('home') }}/chatify">Žinutės</a></li>
                        @endauth
                    </ul>

                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav><!-- End Nav Menu -->

                @auth
                    <a class="btn-getstarted" href="{{ route('logout') }}">Atsijungti</a>
                @else
                    <div class="btn-getstarted-group">
                        <a class="btn-getstarted" href="{{ route('registration') }}">Registruotis</a>
                        <a class="btn-getstarted" href="{{ route('login') }}">Prisijungti</a>
                    </div>
                @endauth
            </div>
        </header><!-- End Header -->

            <!-- Flash Message -->
        @if(session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif

        <div class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text align-items-end justify-content-start">
                    <div class="col-md-12 ftco-animate text-center mb-5">
                        <p class="breadcrumbs mb-0"><span class="mr-3"><a href="">Resursų<i
                                        class="ion-ios-arrow-forward"></i></a></span> <span>puslapis</span></p>
                        <h1 class="mb-3 bread">Raskite resursų jūsų muzikos veiklai</h1>
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
                                <a href=" {{ route('resources.create') }}" class="btn btn-success btn-sm" title="Pridėti naują resursą">
                                    Pridėti naują resursą
                                </a>
                                @foreach ($resources as $resource)
                                    <div class="team d-md-flex p-4 bg-white">
                                        <div class="img-container">
                                            <?php if (file_exists('images/' . $resource->image)) { ?>
                                            <img src="{{ asset('images/' . $resource->image) }}" alt="{{ $resource->name }}">
                                            <?php } ?>
                                        </div>
                                        <div class="text pl-md-4">
                                            <span class="location mb-0"></span>
                                            <h2>{{ $resource->name }}</h2>
                                            <span class="position">{{ $resource->type }}</span>
                                            <p class="mb-2">{{ $resource->description }}</p>
                                            <p><a href="">Daugiau informacijos</a></p>
                                            <a href="{{ url('/student/' . $resource->id) }}" title="View Student"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/student/' . $resource->id . '/edit') }}" title="Edit Student"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/student' . '/' . $resource->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Student" onclick="return confirm("Confirm delete?")"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- @if ($users->isEmpty())
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    Pagal nurodytus paieškos kriterijus, muzikantų nerasta.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else --}}
                                    {{-- @foreach ($users as $user)
                                        <div class="team d-md-flex p-4 bg-white">
                                            <div class="img" style="background-image: url(images/person_2.jpg);"></div>
                                            <div class="text pl-md-4">
                                                @php
                                                    $counties = $user->locations->pluck('county')->sort()->implode(', ');
                                                    $genres = $user->genres->pluck('name')->sort()->implode(', ');
                                                    $specialties = $user->specialties->pluck('name')->sort()->implode(', ');
                                                @endphp
                                                <span class="location mb-0">{{ $counties }}</span>
                                                <h2>{{ $user->name }}</h2>
                                                <span class="position">{{ $genres }}</span>
                                                <p class="mb-2 position">{{ $specialties }}</p>
                                                <p><a href="{{ route('home') }}/chatify/{{ $user->id }}" class="btn btn-primary">Susisiekti</a></p>
                                            </div>
                                        </div>
                                    @endforeach --}}
                                {{-- @endif --}}
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col text-center">
                                <div class="block-27">
                                    <ul>
                                        {{-- {{$users->appends(['search' => $search, 'specialties' => $search_specialties, 'genres' => $search_genres, 'counties' => $search_counties])->onEachSide(1)->links()}} --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 sidebar">
                        {{-- <form action="/searchPost" method="GET" class="browse-form">
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

                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">Ieškoti</button>
                                </div>
                            </div>
                        </form> --}}
                    </div>
                </div>
            </div>
        </section>
        <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    </body>
@endsection

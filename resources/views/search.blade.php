@extends('layout')
@section('title', 'Pagrindinis puslapis')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <body>
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
                        <li><a href="{{ route('home') }}#hero" class="active">Namų puslapis</a></li>
                        @auth
                            <li><a href="{{ route('search') }}">Muzikantų paieška</a></li>
                            <li><a href="{{ route('profile') }}">Mano profilis</a></li>
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
                                @foreach ($users as $user)
                                    <div class="team d-md-flex p-4 bg-white">
                                        <div class="img" style="background-image: url(images/person_2.jpg);"></div>
                                        <div class="text pl-md-4">
                                            @php
                                                $counties = $user->locations->pluck('county')->implode(', ');
                                                $genres = $user->genres->pluck('name')->implode(', ');
                                                $specialties = $user->specialties->pluck('name')->implode(', ');
                                            @endphp
                                            <span class="location mb-0">{{ $counties }}</span>
                                            <h2>{{ $user->name }}</h2>
                                            <span class="position">{{ $genres }}</span>
                                            <p class="mb-2">{{ $specialties }}</p>
                                            <p><a href="#" class="btn btn-primary">Susisiekti</a></p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                               
                        </div>
                        <div class="row mt-5">
                            <div class="col text-center">
                                <div class="block-27">
                                    <ul>
                                        <li><a href="#">&lt;</a></li>
                                        <li class="active"><span>1</span></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">4</a></li>
                                        <li><a href="#">5</a></li>
                                        <li><a href="#">&gt;</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 sidebar">
                        <div class="sidebar-box bg-white p-4 ftco-animate">
                            <h3 class="heading-sidebar">Ieškokite pagal raktažodį</h3>
                            <form action="#" class="search-form mb-3">
                                <div class="form-group">
                                    <span class="icon icon-search"></span>
                                    <input type="text" name="search" class="form-control" placeholder="Search...">
                                </div>
                            </form>

                            <h3 class="heading-sidebar">Pagal specializaciją</h3>
                            <form action="#" class="browse-form">
                                @foreach (config('music_config.specialties') as $specialty)
                                    <label for="option-{{ $loop->iteration }}">
                                        <input type="checkbox" id="option-{{ $loop->iteration }}" name="specialties[]" value="{{ $specialty }}" {{ in_array($specialty, (isset($selected_specialties) ? $selected_specialties : [])) ? 'checked' : '' }}>
                                        {{ $specialty }}
                                    </label>
                                    <br>
                                @endforeach
                            </form>

                            <h3 class="heading-sidebar">Pagal žanrą</h3>
                            <form action="#" class="browse-form">
                                @foreach (config('music_config.genres') as $genre)
                                    <label for="option-{{ $loop->iteration }}">
                                        <input type="checkbox" id="option-{{ $loop->iteration }}" name="genres[]" value="{{ $genre }}" {{ in_array($genre, (isset($selected_genres) ? $selected_genres : [])) ? 'checked' : '' }}>
                                        {{ $genre }}
                                    </label>
                                    <br>
                                @endforeach
                            </form>

                            <h3 class="heading-sidebar">Pagal vietą</h3>
                            <form action="#" class="browse-form">
                                @foreach (config('music_config.counties') as $county)
                                    <label for="option-{{ $loop->iteration }}">
                                        <input type="checkbox" id="option-{{ $loop->iteration }}" name="counties[]" value="{{ $county }}" {{ in_array($county, (isset($selected_counties) ? $selected_counties : [])) ? 'checked' : '' }}>
                                        {{ $county }}
                                    </label>
                                    <br>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- loader -->
        <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke-miterlimit="10" stroke="#F96D00" />
        </svg></div>
    </body>
    </html>
@endsection

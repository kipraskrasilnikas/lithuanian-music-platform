@extends('layout')
@section('title', 'Pagrindinis puslapis')
@section('content')

@section('header')
<body class="index-page" data-bs-spy="scroll" data-bs-target="#navmenu">
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container-fluid d-flex align-items-center justify-content-between">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1>Lietuvos muzikos platforma</h1>
        <span>.</span>
      </a>

      <!-- Nav Menu -->
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}#hero" class="active">Namų puslapis</a></li>
          <li><a href="{{ route('music') }}" class="{{ request()->is('/music') ? 'active' : '' }}">Atraskite muziką</a></li>
          <li><a href="{{ route('search') }}">Muzikantų paieška</a></li>
          <li><a href="{{ route('resources') }}">Ištekliai</a></li>

          @auth
            <li><a href="{{ route('chatify') }}">Žinutės</a></li>
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
</body>
@endsection

<body class="index-page" data-bs-spy="scroll" data-bs-target="#navmenu">
  <main id="main">

    <!-- Hero Section - Home Page -->
    <section id="hero" class="hero">
      <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

      <div class="container">
        <div class="row">
          <div class="col-lg-10">
            <h2 data-aos="fade-up" data-aos-delay="100">Sveiki atvykę</h2>
            <p data-aos="fade-up" data-aos-delay="200">Raskite su kuo kolaboruoti, pasirinkdami specifinius filtrus</p>
          </div>
          <div class="col-lg-10">
            <form action="/searchPost" class="sign-up-form d-flex" data-aos="fade-up" data-aos-delay="300">
              <input type="text" name="search" class="form-control" placeholder="Paieška">

              <div class="select-wrap">
                <select name="specialties[]" class="form-control">
                  <option value="">Pasirinkti specializaciją</option>
                  @foreach (config('music_config.specialties') as $specialty)
                    <option value="{{ $specialty }}" {{ isset($search_specialties[0]) && $search_specialties[0] == $specialty ? 'selected' : '' }}>{{ $specialty }}</option>
                  @endforeach
                </select>
              </div>

              <div class="select-wrap">
                <select name="genres[]" class="form-control">
                  <option value="">Pasirinkti žanrą</option>
                  @foreach (config('music_config.genres') as $genre)
                      <option value="{{ $genre }}" {{ isset($search_genres[0]) && $search_genre == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                  @endforeach
                </select> 
              </div>

              <div class="select-wrap">
                <div class="icon"><span class="bi bi-arrow-up-short"></span></div>
                <select name="counties[]" class="form-control">
                  <option value="">Pasirinkti apskritį</option>
                  @foreach (config('music_config.counties') as $county)
                      <option value="{{ $county }}" {{ isset($search_counties[0]) && $search_county == $county ? 'selected' : '' }}>{{ $county }}</option>
                  @endforeach
                </select> 
              </div>

              <input type="submit" class="btn btn-primary" value="Ieškoti">
            </form>
          </div>
        </div>
      </div>

    </section><!-- End Hero Section -->

  

  <!-- Scroll Top Button -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>
</body>

@endsection
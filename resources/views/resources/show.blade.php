@extends('layout')
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
                    <li><a href="{{ route('home') }}#hero" >Namų puslapis</a></li>
                    <li><a href="{{ route('search') }}">Muzikantų paieška</a></li>
                    <li><a href="{{ route('resource') }}" class="active">Ištekliai</a></li>
                    
                    @auth
                        <li><a href="{{ route('profile') }}">Mano profilis</a></li>
                        <li><a href="{{ route('chatify') }}">Žinutės</a></li>
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

<!-- Flash Message -->
@if(session('flash_message'))
<div class="alert alert-success">
    {{ session('flash_message') }}
</div>
@endif 

<div class="card" style="margin:20px;">
    <div class="card-header">Resurso informacija</div>
    <div class="card-body">
        <h5 class="card-title">Pavadinimas: {{ $resources->name}} </h5>
        <p class="card-text">Tipas: {{ $resources->type}} </p>
        <p class="card-text">Aprašymas: {{ $resources->description}} </p>
        <p class="card-text">Adresas: {{ $resources->address}} </p>
        <p class="card-text">Telefono numeris: {{ $resources->telephone}} </p>
        <p class="card-text">El. paštas: {{ $resources->email}} </p>
        <?php if ($resources->image && file_exists(public_path('images/' . $resources->image))) { ?>
            <p class="card-text">Paveikslėlis:</p>
            <div class="img-container">
                <img src="{{ asset('images/' . $resources->image) }}" alt="{{ $resources->name }}">
            </div>
        <?php } ?>
        <a href="{{ route('resource') }}" class="btn btn-primary">Grįžti į resursų puslapį</a>
        <a href="{{ route('resources.edit', $resources->id) }}" class="btn btn-success">Redaguoti</a>
    </div>
</div>
@stop

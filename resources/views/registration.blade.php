@extends('layout')

@section('title', 'Registracijos puslapis')

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
                        <li><a href="{{ route('resource') }}">Ištekliai</a></li>

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

    <div class="container">
        <h1 class="display-4">Registracija</h1>
        <div class="mt-5">
            @if ($errors->any())
                <div class="col-12">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>

        <form action="{{ route('registration.post') }}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
            @csrf
            <div class="mb-3">
                <label class="form-label">Vardas, pavardė</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" onkeyup="saveValue(this);">
            </div>
            <div class="mb-3">
                <label for="registrationEmailInput">El. pašto adresas</label>
                <input type="email" class="form-control" id="registrationEmailInput" name="email" value="{{ old('email') }}" onkeyup="saveValue(this);">
            </div>
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Slaptažodis</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label for="passwordConfirmationInput" class="form-label">Patvirtinti slaptažodį</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Registruotis</button>
            <div class="no-account">
                <br>
                <a href="{{ route('login') }}">Esate prisiregistravęs? Prisijunkite</a>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        document.getElementById("name").value = getSavedValue("name");
        document.getElementById("registrationEmailInput").value = getSavedValue("registrationEmailInput");

        function saveValue(e){
            var id = e.id;
            var val = e.value;
            localStorage.setItem(id, val);
        }

        function getSavedValue(v){
            if (!localStorage.getItem(v)) {
                return "";
            }

            return localStorage.getItem(v);
        }
    </script>
@endsection

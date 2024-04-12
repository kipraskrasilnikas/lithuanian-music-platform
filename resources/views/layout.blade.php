<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lietuvos Muzikos Platforma')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

      <!-- Favicons -->
      <link href=" {{ asset('assets/img/favicon.png') }}" rel="icon">
      <link href=" {{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

      <!-- Fonts -->
      <link href="https://fonts.googleapis.com" rel="preconnect">
      <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

      <!-- Vendor CSS Files -->
      <link href=" {{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href=" {{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
      <link href=" {{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
      <link href=" {{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
      <link href=" {{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">

      <!-- Template Main CSS File -->
      <link href=" {{ asset('assets/css/main.css') }}" rel="stylesheet">

      <!-- Vendor JS Files -->
      <script src=" {{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <script src=" {{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
      <script src=" {{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
      <script src=" {{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
      <script src=" {{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
      <script src=" {{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
      <script src=" {{ asset('assets/vendor/aos/aos.js') }}"></script>
      <script src=" {{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

      <!-- Template Main JS File -->
      <script src=" {{ asset('assets/js/main.js') }}"></script>
  </head>
  <body>
    @include('include.header')

    @section('header')
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
                    <li><a href="{{ route('home') }}#hero" class="{{ request()->is('/') ? 'active' : '' }}">Namų puslapis</a></li>
                    <li><a href="{{ route('search') }}" class="{{ request()->is('search') ? 'active' : '' }}">Muzikantų paieška</a></li>
                    <li><a href="{{ route('resources') }}" class="{{ request()->is('resources') || request()->is('resource*') ? 'active' : '' }}">Ištekliai</a></li>

                    @auth
                      <li><a href="{{ route('chatify') }}" class="{{ request()->is('chatify') ? 'active' : '' }}">Žinutės</a></li>
                      <li><a href="{{ route('profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">Mano profilis</a></li>
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
    @show

    @yield('content')
  </body>
</html>
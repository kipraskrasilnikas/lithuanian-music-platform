{{-- <header id="header">
    <nav class="navbar navbar-expand-lg navbar-light" role="banner">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}"><img src="images/logo.png" alt="logo"></a>

            <div class="navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 right">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Atsijungti</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">Vartotojų Paieška</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Prisijungti</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('registration') }}">Registruotis</a></li>
                    @endauth
                </ul>
                <span class="navbar-text">
                    @auth
                        <a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
                    @endauth
                </span>
            </div>
        </div><!--/.container-->
    </nav><!--/nav-->
</header><!--/header--> --}}

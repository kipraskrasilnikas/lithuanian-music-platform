<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Atsijungti</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Prisijungti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration') }}">Registruotis</a>
                    </li>
                @endauth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Nusileidžiantis sąrašas
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Veiksmas</a></li>
                        <li><a class="dropdown-item" href="#">Kitas veiksmas</a></li>
                        <li><a class="dropdown-item" href="#">Dar kažkoks kitas veiksmas</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-text">
                @auth
                    <a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
                @endauth
            </span>
        </div>
    </div>
</nav>

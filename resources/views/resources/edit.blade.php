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
                    @auth
                        <li><a href="{{ route('search') }}">Muzikantų paieška</a></li>
                        <li><a href="{{ route('profile') }}">Mano profilis</a></li>
                        <li><a href="{{ route('home') }}/chatify">Žinutės</a></li>
                        <li><a href="{{ route('resource') }}" class="active">Ištekliai</a></li>
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

<div class="card" style="margin:20px;">
  <div class="card-header">Resurso redagavimas</div>
  <div class="card-body">
    <form action="{{ route('resources.update', $resources->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method("POST")

        <label>Pavadinimas</label><br>
        <input type="text" name="name" id="name" class="form-control" value="{{ $resources->name }}" required>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Resurso tipas</label><br>
        <select name="type" id="type" class="form-control" required>
            @foreach(config('music_config.resource_types') as $type)
                <option value="{{ $type }}" {{ $resources->type == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Aprašymas</label><br>
        <textarea name="description" id="description" class="form-control">{{ $resources->description }}</textarea>
        @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Paveikslėlis</label>
        <input type="file" name="image" id="image" class="form-control">
        @if ($resources->image && file_exists(public_path('images/' . $resources->image)))
            <img id="preview_image" src="{{ asset('images/' . $resources->image) }}" alt="Preview" style="width: 100px; height: 100px;"><br>
        @else
            <img id="preview_image" src="#" alt="Preview" style="display: none; width: 100px; height: 100px;"><br>
        @endif
        @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Adresas</label><br>
        <input type="text" name="address" id="address" class="form-control" value="{{ $resources->address }}">
        @error('address')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Telefono numeris</label><br>
        <input type="tel" name="telephone" id="telephone" class="form-control" pattern="^\+?[0-9]{9,}$" oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" value="{{ $resources->telephone }}">
        @error('telephone')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Elektroninis paštas</label><br>
        <input type="email" name="email" id="email" class="form-control" value="{{ $resources->email }}">
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <a href="{{ route('resource') }}" class="btn btn-primary">Grįžti į resursų puslapį</a>
        <input type="submit" value="Atnaujinti" class="btn btn-success">
    </form>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview_image').attr('src', e.target.result);
                $('#preview_image').show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function(){
        readURL(this);
    });

    function InvalidMsg(textbox) {
        if (textbox.validity.patternMismatch){
            textbox.setCustomValidity('Telefono numerį turi sudaryti bent 9 skaičiai!');
        }    
        else {
            textbox.setCustomValidity('');
        }

        return true;
    }
</script>

@stop
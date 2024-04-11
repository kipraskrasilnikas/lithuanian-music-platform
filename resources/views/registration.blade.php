@extends('layout')
@section('title', 'Registracijos puslapis')
@section('content')
    <div class="container">
        <h1 class="display-4">Registracija</h1>
        <div class="mt-5">
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
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="registrationEmailInput">El. pašto adresas</label>
                <input type="email" class="form-control" id="registrationEmailInput" name="email" value="{{ old('email') }}" onkeyup="saveValue(this);">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Slaptažodis</label>
                <input type="password" class="form-control" name="password">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="passwordConfirmationInput" class="form-label">Patvirtinti slaptažodį</label>
                <input type="password" class="form-control" name="password_confirmation">
                @error('password_confirmation')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
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

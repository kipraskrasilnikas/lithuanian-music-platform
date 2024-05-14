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
                <label class="form-label">Atlikėjo slapyvardis</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="registrationEmailInput">El. pašto adresas</label>
                <input type="email" class="form-control" id="registrationEmailInput" name="email" value="{{ old('email') }}" oninvalid="InvalidEmailMsg(this);" oninput="InvalidEmailMsg(this);">
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
            <button type="submit" class="btn red-button">Registruotis</button>
            <div class="no-account">
                <br>
                <a href="{{ route('login') }}">Esate prisiregistravęs? Prisijunkite</a>
            </div>
        </form>
    </div>

    <script>
        function InvalidEmailMsg(input) {
            if (input.validity.typeMismatch){
                input.setCustomValidity('Elektroninio pašto formatas neteisingas!');
            }    
            else {
                input.setCustomValidity('');
            }
            return true;
        } 
    </script>
@endsection

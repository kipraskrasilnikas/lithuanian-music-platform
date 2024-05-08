@extends('layout')
@section('title', 'Prisijungimo puslapis')
@section('content')
    <div class="container">
        <h1 class="display-4">Prisijungimas</h1>

        <div class="mt-5">
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
            @csrf
            <div class="mb-3">
                <label for="loginEmailInput">El. pašto adresas</label>
                <input type="email" class="form-control" id="loginEmailInput" name="email" value="{{ old('email') }}" onkeyup="saveValue(this);">
            </div>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Slaptažodis</label>
                <input type="password" class="form-control" name="password">
            </div>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary">Prisijungti</button>

            <div class="no-account">
                <br>
                <a href="{{ route('registration') }}">Neturite paskyros? Prisiregistruokite</a>
            </div>
        </form>
    </div>
@endsection

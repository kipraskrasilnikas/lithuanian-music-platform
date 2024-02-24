@extends('layout')

@section('title', 'Profile')

@section('content')
    <div class="container">
        <h1 class="display-4">Profile page</h1>

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

        <form action="{{ route('profile.post') }}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
            @csrf
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
                <label for="loginEmailInput">Email address</label>
                <input type="email" class="form-control" id="loginEmailInput" name="email" value="{{ $user->email }}">
            </div>
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label for="passwordConfirmationInput" class="form-label">Confirm password</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection

@extends('layout')

@section('title', 'Prisijungimo puslapis')

@section('content')
    <div class="container">
        <h1 class="display-4">Prisijungimas</h1>

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

        <form action="{{ route('login.post') }}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
            @csrf
            <div class="mb-3">
                <label for="loginEmailInput">El. pašto adresas</label>
                <input type="email" class="form-control" id="loginEmailInput" name="email" value="{{ old('email') }}" onkeyup="saveValue(this);">
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Slaptažodis</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Prisijungti</button>
        </form>
    </div>

    <script type="text/javascript">
        document.getElementById("loginEmailInput").value = getSavedValue("loginEmailInput");

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

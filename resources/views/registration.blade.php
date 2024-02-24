@extends('layout')

@section('title', 'Registration')

@section('content')
    <div class="container">
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
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" onkeyup="saveValue(this);">
            </div>
            <div class="mb-3">
                <label for="registrationEmailInput">Email address</label>
                <input type="email" class="form-control" id="registrationEmailInput" name="email" value="{{ old('email') }}" onkeyup="saveValue(this);">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
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

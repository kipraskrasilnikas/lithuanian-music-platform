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
            <div class="mb-3" style="font-size: 30px;">
                <label class="form-label">Locations</label>
            </div>

            <table class="table table-bordered" id="table">
                <tr>
                    <th>County</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>
                        <select name="locations[0][county]" class="form-control">
                            <option value="">Select County</option>
                            @foreach ($counties as $county)
                                <option value="{{ $county }}">{{ $county }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" name="locations[0][county]" placeholder="Enter your County" class="form-control"> --}}
                    </td>
                    <td>
                        <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                    </td>
                </tr>
            </table>

            {{-- <div class="mb-3">
                <button type="button" class="btn btn-primary" id="add-location">Add Location</button>
            </div> --}}
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function() {
            var i = 0;
            $('#add').click(function() {
                ++i;
                // cia irgi kitaip turi atrodyt tas value={{$county}}, pagal indeksa reikes imt, bet neskamba sunku
                $('#table').append(
                    `<tr>
                        <td>
                            <select name="locations[` + i + `][county]" class="form-control">
                                <option value="">Select County</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county }}">{{ $county }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-table-row">Remove</button>
                        </td>
                    </tr>`);
            });

            $(document).on('click', '.remove-table-row', function() {
                $(this).parents('tr').remove();
            });

        });
    </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endsection

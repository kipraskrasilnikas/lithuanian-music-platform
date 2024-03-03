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
            <div id="locations">
                <div id="location-template" style="display: none;">
                    <div class="mb-3 location">
                        <div class="mb-3" style="font-size: 20px;">
                            <label class="form-label" id="location-name"></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">County</label>
                            <select class="form-control" name="locations[][county]" required>
                                <option value="">Select County</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county }}">{{ $county }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="locations[][city]" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="locations[][address]">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Postcode</label>
                            <input type="text" class="form-control" name="locations[][postcode]">
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-danger delete-location">Delete</button>
                        </div>
                    </div>
                </div>
    
                @foreach ($user->locations as $index => $location)
                    <div data-id="{{ $index }}" class="mb-3 location">
                        <div class="mb-3" style="font-size: 20px;">
                            <label class="form-label" id="location-name">Location {{$index}}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">County</label>
                            <select class="form-control" name="locations[][county]" required>
                                <option value="">Select County</option>
                                @foreach ($counties as $county)
                                    <option value="{{ $county }}" @if ($county === $location->county) selected @endif>{{ $county }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="locations[][city]" value="{{ $location->city }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="locations[][address]" value="{{ $location->address }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Postcode</label>
                            <input type="text" class="form-control" name="locations[][postcode]" value="{{ $location->postcode }}">
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-danger delete-location">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mb-3">
                <button type="button" class="btn btn-primary" id="add-location">Add Location</button>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("add-location").addEventListener("click", function() {
                var locationsCount = document.querySelectorAll(".location").length;
                if (locationsCount < 4) {
                    // gaunam paskutinio indeksa, jei nera, priskiriam 0
                    var index = $("#locations").find(".location:last").data("id") ? ($("#locations").find(".location:last").data("id") + 1): 1;

                    var locationTemplateClone = $("#location-template").children().clone();
                    $("#locations").append(locationTemplateClone);
                    
                    var lastLocation = $(".location:last");

                    lastLocation.attr("data-id", index);
                    lastLocation.find("#location-name").text("Location " + index);
                } else {
                    alert("You can only add up to 3 locations.");
                }
            });

            // Event listener for delete buttons
            $(document).on("click", ".delete-location", function() {
                // Remove the parent location div
                $(this).closest(".location").remove();

                // Update the indices of the remaining locations
                $(".location").each(function(index) {
                    $(this).attr("data-id", index);
                    $(this).find("#location-name").text("Location " + (index));
                });
            });

        });
    </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endsection

@extends('layout')

@section('title', 'Profilio puslapis')

@section('content')
    <div class="container">
        <h1 class="display-4">Profilis</h1>

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
                <label class="form-label">Vardas, Pavardė</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
                <label for="loginEmailInput">El. pašto adresas</label>
                <input type="email" class="form-control" id="loginEmailInput" name="email" value="{{ $user->email }}">
            </div>
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Slaptažodis</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label for="passwordConfirmationInput" class="form-label">Patvirtinti slaptažodį</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
            <div class="mb-3">
                <label for="specialtyInput" class="form-label">Specializacija<span style="color: red;">*</span></label>
                <br>
                @foreach (config('music_config.specialties') as $specialty)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="specialty-{{ $loop->iteration }}" name="specialties[]" value="{{ $specialty }}" 
                            {{ in_array($specialty, $user_specialties->pluck('name')->toArray()) ? 'checked' : '' }}>
                        <label class="form-check-label" for="specialty-{{ $loop->iteration }}">
                            {{ $specialty }}
                        </label>
                    </div>
                @endforeach
            </div>             
            <div class="mb-3">
                <label for="genreInput" class="form-label">Žanras<span style="color: red;">*</span></label>
                <br>
                @foreach (config('music_config.genres') as $genre)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="genre-{{ $loop->iteration }}" name="genres[]" value="{{ $genre }}" 
                            {{ in_array($genre, $user_genres->pluck('name')->toArray()) ? 'checked' : '' }}>
                        <label class="form-check-label" for="specialty-{{ $loop->iteration }}">
                            {{ $genre }}
                        </label>
                    </div>
                @endforeach
            </div>                   
            <div class="mb-3" style="font-size: 30px;">
                <label class="form-label">Vietos (galima iki 3-jų)</label>
            </div>
            <table class="table table-bordered" id="table">
                <tr>
                    <th>Apskritis<span style="color: red;">*</span></th>
                    <th>Miestas<span style="color: red;">*</span></th>
                    <th>Adresas</th>
                    <th>Veiksmas</th>
                </tr>
                <tr>
                    <td>
                        <select name="locations[0][county]" class="form-control">
                            <option value="">Pasirinkti apskritį </option>
                            @foreach (config('music_config.counties') as $county)
                                <option value="{{ $county }}" {{ $locations[0]->county == $county ? 'selected' : '' }} >{{ $county }}</option>
                            @endforeach
                        </select>                    
                    </td>
                    <td>
                        <input type="text" name="locations[0][city]" value="{{ $locations[0]->city }}" placeholder="Įveskite miestą" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="locations[0][address]" value="{{ $locations[0]->address }}" placeholder="Įveskite adresą" class="form-control">
                    </td>
                    <td>
                        <button type="button" name="add" id="add" class="btn btn-success">Pridėti daugiau</button>
                    </td>
                </tr>
            </table>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Išsaugoti</button>
            </div>
        </form>
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", function() {
            var form_location_i = 0;
            var limit = 2;
            var locations = {!! json_encode($locations) !!};
            var dbLocationCount = Object.keys(locations).length;

            while (form_location_i < limit && form_location_i < dbLocationCount - 1) {
                addLocationRow(true);
            }

            $('#add').click(function() {
                if (form_location_i < limit) {
                    addLocationRow();
                } else {
                    alert('Maksimalus vietų skaičius yra 3!');
                }
            });

            $(document).on('click', '.remove-table-row', function() {
                $(this).parents('tr').remove();
            });

            function addLocationRow(isFromDB) {
                ++form_location_i;

                if (isFromDB) {
                    $('#table').append(
                        `<tr>
                            <td>
                                <select name="locations[` + form_location_i + `][county]" class="form-control">
                                    <option value="">Pasirinkti apskritį</option>
                                    @foreach (config('music_config.counties') as $county)
                                        <option value="{{ $county }}" ${(locations[form_location_i].county == "{{$county}}") ? 'selected' : '' }>{{ $county }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="locations[` + form_location_i + `][city]" value="${locations[form_location_i].city}" placeholder="Įveskite miestą" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="locations[` + form_location_i + `][address]" value="${(locations[form_location_i].address) ? locations[form_location_i].address : ''}" placeholder="Įveskite adresą" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-table-row">Pašalinti</button>
                            </td>
                        </tr>`);

                } else {
                    $('#table').append(
                        `<tr>
                            <td>
                                <select name="locations[` + form_location_i + `][county]" class="form-control">
                                    <option value="">Pasirinkti apskritį</option>
                                    @foreach (config('music_config.counties') as $county)
                                        <option value="{{ $county }}">{{ $county }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="locations[` + form_location_i + `][city]" placeholder="Įveskite miestą" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="locations[` + form_location_i + `][address]" placeholder="Įveskite adresą" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-table-row">Pašalinti</button>
                            </td>
                        </tr>`);
                }
            }
        });
    </script>

@endsection

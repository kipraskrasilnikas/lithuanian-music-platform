@extends('layout')
@section('title', 'Profilio puslapis')
@section('content')
    <body class="blog-page" data-bs-spy="scroll" data-bs-target="#navmenu"> 
        <h1 class="display-4 text-center">Profilis</h1>

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

        <form action="{{ route('profile.post') }}" method="POST" class="ms-auto me-auto mt-3 form-center" style="width: 80%;">
            @csrf
            <div style="text-align: center;">
                <div class="mb-3" style="font-size: 30px;">
                    <label class="form-label">Bendra informacija</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Vardas, Pavardė<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" style="width: 30%; margin-right" align="center">
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="loginEmailInput">El. pašto adresas<span style="color: red;">*</span></label>
                    <input type="email" class="form-control" id="loginEmailInput" name="email" value="{{ $user->email }}">
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
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Aprašymas</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $user->description }}</textarea>
                    <div id="description-counter" class="form-text text-muted">Characters left: <span id="description-count">{{ 500 - strlen($user->description) }}</span></div>
                </div>
                <div class="mb-3" style="font-size: 30px;">
                    <label class="form-label">Muzikos specifikacija</label>
                </div>
                <div class="mb-3" style="font-size: 15px; margin-top: -30px;">
                    <label class="form-label">(Nurodykite, kad jus rastų galimi kolaborantai)</label>
                </div>
            </div>
           
            <div style="margin: auto; width: 30%">
                <div class="mb-3">
                    <label for="specialtyInput" class="form-label">Specializacija<span style="color: red;">*</span></label>
                    <br>
                    @foreach (config('music_config.specialties') as $specialty)
                        <div class="form-check checkboxes">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="specialties[]" value="{{ $specialty }}" {{ in_array($specialty, $user_specialties->pluck('name')->toArray()) ? 'checked' : '' }}>
                                <span>{{ $specialty }}</span>
                            </label>
                        </div>
                    @endforeach
                    @error('specialties')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>             
                <div class="mb-3">
                    <label for="genreInput" class="form-label">Žanras<span style="color: red;">*</span></label>
                    <br>
                    @foreach (config('music_config.genres') as $genre)
                        <div class="form-check checkboxes">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre }}" {{ in_array($genre, $user_genres->pluck('name')->toArray()) ? 'checked' : '' }}>
                                <span>{{ $genre }}</span>
                            </label>
                        </div>
                    @endforeach
                    @error('genres')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="moodInput" class="form-label">Jūsų muzikos nuotaikos</label>
                    <br>
                    @foreach (config('music_config.music_moods') as $mood_category => $mood_details)
                        <li style="list-style-type: none;">
                            <div class="form-check checkboxes" style="color: {{ $mood_details['color_hex'] }}; ">
                                <label class="form check-label">
                                    <input class="form-check-input mood-category" type="checkbox" id="mood-category-{{ $loop->iteration}}">
                                    <span>{{ $mood_category }}</span>
                                </label>
                            </div>
                            <ul style="list-style-type: none;">
                                @foreach ($mood_details['moods'] as $mood)
                                <li style="list-style-type: none;">
                                    <div class="form-check checkboxes" style="color: {{ $mood_details['color_hex'] }}">
                                        <label class="form-check-label" for="mood-{{ $loop->iteration }}">
                                            <input class="form-check-input subOption" type="checkbox" id="mood-{{ $loop->iteration }}" name="moods[]" value="{{ $mood }}" {{ in_array($mood, $user_moods->pluck('mood')->toArray()) ? 'checked' : '' }}>
                                            <span>{{ $mood }}</span>
                                        </label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                    @error('moods')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3" style="font-size: 30px; text-align: center;">
                <label class="form-label">Vietos (galima iki 3-jų)</label>
            </div>
            <div class="mb-3">
                <table class="table table-bordered" id="location_table" style="margin: auto; width: 60%">
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
                            <button type="button" name="add" id="add_location" class="btn btn-success">Pridėti daugiau</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="text-align: center;">
                @error('locations.*.county')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('locations.*.city')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="mb-3" style="font-size: 30px; text-align: center;">
                    <label class="form-label">Jūsų dainos (galima iki 7-ių)</label>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <table class="table table-bordered" id="songs_table">
                    <tr>
                        <th>Pavadinimas<span style="color: red;">*</span></th>
                        <th>Nuoroda<span style="color: red;">*</span></th>
                        <th>Muzikos žanrai<span style="color: red;">*</span></th>
                        <th>Muzikos nuotaikos<span style="color: red;">*</span></th>
                        <th>Veiksmas</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="songs[0][title]" placeholder="Įveskite pavadinimą" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="songs[0][link]" placeholder="Įveskite nuorodą" class="form-control">
                        </td>
                        <td>
                            <select class="form-select" name="songs[0][genres][]" multiple>
                                @foreach (config('music_config.genres') as $genre)
                                    <option value="{{ $genre }}">{{ $genre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-select" name="songs[0][moods][]" multiple>
                                @foreach (config('music_config.music_moods') as $mood_category => $mood_details)
                                    <optgroup label="{{ $mood_category }}">
                                        @foreach ($mood_details['moods'] as $mood)
                                            <option value="{{ $mood }}">{{ $mood }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="button" name="add" id="add_song" class="btn btn-success">Pridėti daugiau</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="mb-3" style="font-size: 30px; text-align: center;">
                <label class="form-label">Naudotojo statusas</label>
            </div>

            <div style="margin: auto; width: 10%">
                <div class="mb-5">
                    <div class="form-check checkboxes">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="status" value="1" {{ $user->status == 1 ? 'checked' : '' }}>
                            <span>Aktyvus</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Išsaugoti</button>
            </div>
        </form>
        <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    </body>

    <script>

        document.addEventListener("DOMContentLoaded", function() {
            // ------Update description characters-----
            var descriptionInput = document.getElementById('description');
            var descriptionCounter = document.getElementById('description-count');

            // Update character count on input
            descriptionInput.addEventListener('input', function() {
                var remainingChars = 500 - this.value.length;
                descriptionCounter.textContent = remainingChars;
            });
            // -----------------------------------------

            var form_location_i = 0;
            var limit = 2;
            var locations = {!! json_encode($locations) !!};
            var dbLocationCount = Object.keys(locations).length;

            while (form_location_i < limit && form_location_i < dbLocationCount - 1) {
                addLocationRow(true);
            }

            $('#add_location').click(function() {
                if (form_location_i < limit) {
                    addLocationRow();
                } else {
                    alert('Maksimalus vietų skaičius yra 3!');
                }
            });

            $(document).on('click', '.remove-table-row', function() {
                $(this).parents('tr').remove();
                form_location_i--;
            });

            function addLocationRow(isFromDB) {
                ++form_location_i;

                if (isFromDB) {
                    $('#location_table').append(
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
                    $('#location_table').append(
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

        // Check all nested checkboxes
        $('.mood-category').change( function() {
            $(this).closest('div').next('ul').find(':checkbox').prop('checked', this.checked);
        }); 
    </script>

@endsection

@extends('layout')
@section('title', 'Profilio puslapis')
@section('content')
    <body class="profile-page" data-bs-spy="scroll" data-bs-target="#navmenu"> 
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
                    <label class="form-label">Vartotojo vardas<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
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
                    <label for="loginEmailInput">Profilio nuotrauka<span style="color: red;">*</span></label>
                    
                    <input type="button" class="form-control mb-2 mt-2 red-button" value="Įkelkite paveiksliuką" onclick="document.getElementById('image-input').click();" />
                    <input type="file" style="display:none;"  name="avatar" id="image-input" accept="image/*">

                    <div id="image-preview"></div>
                    <input type="hidden" name="base64_image" id="base64-image">
                    <button type="button" id="image-submit">Išsaugoti paveiksliuką</button>

                    <div id="success-message" class="alert alert-success" style="display: none;">Paveiksliukas įkeltas sėkmingai!</div>
                    <div id="no-image-message" class="alert alert-danger" style="display: none;">Įkelkite paveiksliuką prieš išsaugant!</div>
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
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $user->description) }}</textarea>
                    <div id="description-counter" class="form-text text-muted">Characters left: <span id="description-count">{{ 500 - strlen($user->description) }}</span></div>
                </div>
                <div class="mb-3" style="font-size: 30px;">
                    <label class="form-label">Muzikos specifikacija</label>
                </div>
                <div class="mb-3" style="font-size: 15px; margin-top: -30px;">
                    <label class="form-label">(Nurodykite, kad jus rastų kiti muzikantai)</label>
                </div>
            </div>
           
            <div style="margin: auto; width: 30%">
                <div class="mb-3">
                    <label for="specialtyInput" class="form-label">Specializacija<span style="color: red;">*</span></label>
                    <br>
                    @foreach (config('music_config.specialties') as $specialty)
                        <div class="form-check checkboxes">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="specialties[]" value="{{ $specialty }}" {{ (is_array(old('specialties')) && in_array($specialty, old('specialties'))) || (in_array($specialty, $user_specialties->pluck('name')->toArray())) ? 'checked' : '' }}>
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
                                <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre }}" {{ (is_array(old('genres')) && in_array($genre, old('genres'))) || (in_array($genre, $user_genres->pluck('name')->toArray())) ? 'checked' : '' }}>
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
                                            <input class="form-check-input subOption" type="checkbox" id="mood-{{ $loop->iteration }}" name="moods[]" value="{{ $mood }}" {{ (is_array(old('moods')) && in_array($mood, old('moods'))) || (in_array($mood, $user_moods->pluck('mood')->toArray())) ? 'checked' : '' }}>
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
                                <option value="">Pasirinkti apskritį</option>
                                @foreach (config('music_config.counties') as $county)
                                    <option value="{{ $county }}" 
                                        @if(old("locations.0.county") === $county || (!old("locations.0.county") && isset($locations[0]) && $locations[0]->county == $county))
                                            selected
                                        @endif
                                    >{{ $county }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="locations[0][city]" value="{{ old("locations.0.city", (isset($locations[0]) ? $locations[0]->city : '')) }}" placeholder="Įveskite miestą" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="locations[0][address]" value="{{ old("locations.0.address", (isset($locations[0]) ? $locations[0]->address : '')) }}" placeholder="Įveskite adresą" class="form-control">
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
                        <th>YouTube nuoroda<span style="color: red;">*</span></th>
                        <th>Muzikos žanrai</th>
                        <th>Muzikos nuotaikos</th>
                        <th>Veiksmas</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="songs[0][title]" placeholder="Įveskite pavadinimą" class="form-control" value="{{ old("songs.0.title", ($songs[0]->title ?? '')) }}">
                        </td>
                        <td>
                            <input type="text" name="songs[0][original_url]" placeholder="Įveskite nuorodą" class="form-control" value="{{ old("songs.0.original_url", ($songs[0]->original_url ?? '')) }}">
                            @if(isset($songs[0]) && $songs[0]->embedded_url)
                                <div class="youtube-preview" style="padding: 10px;">
                                    <iframe width="280" height="160" src="{{ old("song.0.original_url", $songs[0]->embedded_url) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif
                        </td>
                        <td>
                            <select class="form-select" name="songs[0][genres][]" multiple>
                                @foreach (config('music_config.genres') as $genre)
                                    <option value="{{ $genre }}" {{ (is_array(old('songs.0.genres')) && in_array($genre, old('songs.0.genres'))) || (in_array($genre, $songs[0]->genres ?? [])) ? 'selected' : ''}}>{{ $genre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-select" name="songs[0][moods][]" multiple>
                                @foreach (config('music_config.music_moods') as $mood_category => $mood_details)
                                    <optgroup label="{{ $mood_category }}">
                                        @foreach ($mood_details['moods'] as $mood)
                                            <option value="{{ $mood }}" {{ (is_array(old('songs.0.moods')) && in_array($mood, old('songs.0.moods'))) || (in_array($mood, $songs[0]->moods ?? [])) ? 'selected' : '' }}>{{ $mood }}</option>
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
            <div style="text-align: center;">
                @error('songs.*.title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('songs.*.original_url')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3" style="font-size: 30px; text-align: center;">
                <label class="form-label">Naudotojo statusas</label>
            </div>
            <div style="margin: auto; width: 10%">
                <div class="mb-5">
                    <div class="form-check checkboxes">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status') == 'on' || old('status') == 1 ? 'checked' : ($user->status == 1 ? 'checked' : '') }}>
                            <span>Aktyvus</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn red-button">Išsaugoti</button>
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

            // Location table logic
            var form_location_i = 0;
            var location_limit = 2;
            var locations = {!! json_encode($locations) !!};
            var dbLocationCount = Object.keys(locations).length;

            while (form_location_i < location_limit && form_location_i < dbLocationCount - 1) {
                addLocationRow(true);
            }

            $('#add_location').click(function() {
                if (form_location_i < location_limit) {
                    addLocationRow();
                } else {
                    alert('Maksimalus vietų skaičius yra 3!');
                }
            });

            $(document).on('click', '.remove-location-table-row', function() {
                $(this).parents('tr').remove();
                form_location_i--;
            });

            function addLocationRow(isFromDB) {
                ++form_location_i;

                $('#location_table tbody').append(`
                    <tr>
                        <td>
                            <select name="locations[` + form_location_i + `][county]" class="form-control">
                                <option value="">Pasirinkti apskritį</option>
                                @foreach (config('music_config.counties') as $county)
                                    <option value="{{ $county }}" ${(isFromDB && (locations[form_location_i].county === "{{ $county }}" || old("locations." + form_location_i + ".county"))) ? 'selected' : ''}>{{ $county }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="locations[` + form_location_i + `][city]" value="${isFromDB ? (locations[form_location_i].city ?? '') : ''}" placeholder="Įveskite miestą" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="locations[` + form_location_i + `][address]" value="${isFromDB ? (locations[form_location_i].address ?? '') : ''}" placeholder="Įveskite adresą" class="form-control">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-location-table-row">Pašalinti</button>
                        </td>
                    </tr>
                `);
            }
            // Location table logic END

            // Song table logic
            var form_song_i = 0;
            var song_limit = 6;
            var songs = {!! json_encode($songs) !!};
            var dbSongCount = Object.keys(songs).length;

            while (form_song_i < song_limit && form_song_i < dbSongCount - 1) {
                addSongRow(true);
            }

            $('#add_song').click(function() {
                console.log('paspaustas add song');
                console.log(form_song_i, song_limit);
                if (form_song_i < song_limit) {
                    addSongRow();
                } else {
                    alert('Galima ikelti iki 7 dainų!');
                }
            });

            // cia selectoriu reik pakeist
            $(document).on('click', '.remove-song-table-row', function() {
                $(this).parents('tr').remove();
                form_song_i--;
            });

            function addSongRow(isFromDB) {
                ++form_song_i;

                var youtubePreview = ''; // Initialize youtubePreview variable

                if (isFromDB && songs[form_song_i]?.embedded_url) {
                    youtubePreview = `<div class="youtube-preview" style="padding: 10px;">
                                        <iframe width="280" height="160" src="${songs[form_song_i].embedded_url}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>`;
                }

                $('#songs_table tbody').append(
                    `<tr>
                        <td>
                            <input type="text" name="songs[` + form_song_i + `][title]" value="${isFromDB ? (songs[form_song_i].title ?? '') : ''}" placeholder="Įveskite pavadinimą" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="songs[` + form_song_i + `][original_url]" value="${isFromDB ? (songs[form_song_i].original_url ?? '') : ''}" placeholder="Įveskite nuorodą" class="form-control">
                            ${youtubePreview}
                        </td>
                        <td style="vertical-align: top;">
                            <select class="form-select" name="songs[` + form_song_i + `][genres][]" multiple style="height: 210px;">
                                @foreach (config('music_config.genres') as $genre)
                                    <option value="{{ $genre }}" ${( isFromDB && (songs[form_song_i].genres.includes("{{ $genre }}") ? 'selected' : ''))}>{{ $genre }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="vertical-align: top;">
                            <select class="form-select" name="songs[` + form_song_i + `][moods][]" multiple style="height: 210px;">
                                @foreach (config('music_config.music_moods') as $mood_category => $mood_details)
                                    <optgroup label="{{ $mood_category }}">
                                        @foreach ($mood_details['moods'] as $mood)
                                            <option value="{{ $mood }}" ${( isFromDB && (songs[form_song_i].moods.includes("{{ $mood }}") ? 'selected' : ''))}>{{ $mood }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-song-table-row">Pašalinti</button>
                        </td>
                    </tr>
                `);
            }
            // Song table logic END
        });

        // Check all nested checkboxes
        $('.mood-category').change( function() {
            $(this).closest('div').next('ul').find(':checkbox').prop('checked', this.checked);
        }); 
    </script>

    <script>
        $(document).ready(function() {
            var preview = new Croppie($('#image-preview')[0], {
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle',
                },
                boundary: {
                    width: 200,
                    height: 200
                },
                enableOrientation: true,
                enableExif: true,
            });

            var avatarSrc = "{{ $user->avatar && file_exists(public_path('images/' . $user->avatar)) ? asset('images/' . $user->avatar) : '' }}";
            if (avatarSrc) {
                preview.bind({
                    url: avatarSrc
                }).then(function() {
                    console.log('Avatar loaded');
                }).catch(function(error) {
                    console.error('Error loading avatar:', error);
                });
            }

            $('#image-input').on('change', function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();

                reader.onload = function() {
                    var base64data = reader.result;
                    $('#base64-image').val(base64data);

                    preview.bind({
                        url: base64data
                    }).then(function() {
                        console.log('Croppie bind complete');
                        preview.result('base64').then(function(result) {
                            $('#base64-image').val(result);
                        });
                    });
                }

                reader.readAsDataURL(file);
            });

            $('#image-submit').on('click', function() {
                if ($('#base64-image').val() === '') {
                    $('#no-image-message').fadeIn().delay(8000).fadeOut(); // Show no image message
                } else {
                    preview.result('base64').then(function(result) {
                        $('#base64-image').val(result);
                        $('#success-message').fadeIn().delay(8000).fadeOut(); // Show success message
                    });
                }
            });
        });
    </script>
@endsection

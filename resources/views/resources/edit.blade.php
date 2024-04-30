@extends('layout')
@section('content')

<div class="card" style="margin:20px;">
  <div class="card-header">Resurso redagavimas</div>
  <div class="card-body">
    <form action="{{ route('resources.update', $resources->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method("POST")

        <div class="mb-3">
            <label>Pavadinimas<span style="color: red;">*</span></label><br>
            <input type="text" name="name" id="name" class="form-control" value="{{ $resources->name }}" required>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Resurso tipas</label><br>
            <select name="type" id="type" class="form-control" required>
                @foreach(config('music_config.resource_types') as $type)
                    <option value="{{ $type }}" {{ $resources->type == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Aprašymas</label><br>
            <textarea name="description" id="description" class="form-control">{{ $resources->description }}</textarea>
            @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Paveikslėlis</label>
            <input type="button" style="width: 15%" class="form-control mb-2 mt-2 red-button" value="Įkelkite paveiksliuką" onclick="document.getElementById('image').click();" />
            <input type="file" style="display:none;"  name="image" id="image" accept="image/*">
            <small class="text-muted">Leidžiami formatai: jpeg, png, jpg, gif</small><br>
            @if ($resources->image && file_exists(public_path('images/' . $resources->image)))
                <img id="preview_image" src="{{ asset('images/' . $resources->image) }}" alt="Preview" style="width: 100px; height: 100px;"><br>
            @else
                <img id="preview_image" src="#" alt="Preview" style="display: none; width: 100px; height: 100px;"><br>
            @endif
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Apskritis<span style="color: red;">*</span></label>
            <select name="county" class="form-control">
                <option value="">Pasirinkti apskritį</option>
                @foreach (config('music_config.counties') as $county)
                    <option value="{{ $county }}" {{ $resources->county == $county ? 'selected' : '' }}>{{ $county }}</option>
                @endforeach
            </select>
            @error('county')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label>Adresas<span style="color: red;">*</span></label><br>
            <input type="text" name="address" id="address" class="form-control" value="{{ $resources->address }}">
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Telefono numeris<span style="color: red;">*</span></label><br>
            <input type="tel" name="telephone" id="telephone" class="form-control" pattern="^\+?[0-9]{9,}$" oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" value="{{ $resources->telephone }}">
            @error('telephone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label>Elektroninis paštas<span style="color: red;">*</span></label><br>
            <input type="email" name="email" id="email" class="form-control" value="{{ $resources->email }}">
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <a href="{{ route('resources') }}" class="btn btn-primary">Grįžti į resursų puslapį</a>
        <input type="submit" value="Atnaujinti" class="btn btn-success">
    </form>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview_image').attr('src', e.target.result);
                $('#preview_image').show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image").change(function(){
        readURL(this);
    });

    function InvalidMsg(textbox) {
        if (textbox.validity.patternMismatch){
            textbox.setCustomValidity('Telefono numerį turi sudaryti bent 9 skaičiai!');
        }    
        else {
            textbox.setCustomValidity('');
        }

        return true;
    }
</script>

@stop
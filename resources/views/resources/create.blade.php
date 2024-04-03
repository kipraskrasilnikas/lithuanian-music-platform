@extends('layout')
@section('content')
<div class="card" style="margin:20px;">
  <div class="card-header">Įkelti naują resursą</div>
  <div class="card-body">
    <form action="{{ route('resources.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <label>Pavadinimas</label><br>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Resurso tipas</label><br>
        <select name="type" id="type" class="form-control" required>
            @foreach(config('music_config.resource_types') as $type)
                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Aprašymas</label><br>
        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Paveikslėlis</label>
        <input type="file" name="image" id="image" class="form-control">
        <img id="preview_image" src="#" alt="Preview" style="display: none; width: 100px; height: 100px;"><br>
        @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Adresas</label><br>
        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
        @error('address')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Telefono numeris</label><br>
        <input type="tel" name="telephone" id="telephone" class="form-control" pattern="^\+?[0-9]{9,}$" oninvalid="InvalidMsg(this);" oninput="InvalidMsg(this);" value="{{ old('telephone') }}">
        @error('telephone')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    
        <label>Elektroninis paštas</label><br>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="submit" value="Įkelti" class="btn btn-success">
    </form>
  </div>
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

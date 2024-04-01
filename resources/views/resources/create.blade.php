@extends('layout')
@section('content')
<div class="card" style="margin:20px;">
  <div class="card-header">Įkelti naują resursą</div>
  <div class="card-body">

    <form action="{{ route('resources.store') }}" method="post">
        @csrf

        <label>Pavadinimas</label><br>
        <input type="text" name="name" id="name" class="form-control"><br>

        <label>Resurso tipas</label><br>
        <select name="type" id="type" class="form-control">
            @foreach(config('music_config.resource_types') as $type)
            <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select><br>

        <label>Aprašymas</label><br>
        <textarea name="description" id="description" class="form-control"></textarea><br>

        <label>Paveikslėlis</label><br>
        <input type="file" name="image" id="image" class="form-control"><br>

        <label>Adresas</label><br>
        <input type="text" name="address" id="address" class="form-control"><br>

        <label>Telefono numeris</label><br>
        <input type="tel" name="telephone" id="telephone" class="form-control"><br>

        <label>Elektroninis paštas</label><br>
        <input type="email" name="email" id="email" class="form-control"><br>

        <input type="submit" value="Save" class="btn btn-success"><br>
    </form>

  </div>
</div>

@stop

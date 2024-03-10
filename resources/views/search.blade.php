@extends('layout')

@section('title', 'Paieškos puslapis')

@section('content')
    <div class="container">
        <h1 class="text-center pt-4 ">Paieška</h1>
        <hr>
        <div class="row py-2">
            <div class="col-md-6">
                <div class="form-group">
                    <form method="get" action="/searchPost">
                        <div class="input-group">
                            <input class="form-control" name="search" placeholder="Search..." value="{{ isset($search) ? $search : ''}}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert">
            Jei muzikanto informacijoje nerandate paieškoje nurodytos vietos, patikrinkite visas muzikanto nurodytas vietas.
        </div>
         <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Vardas, pavardė</th>
                <th scope="col">Specializacija</th>
                <th scope="col">Žanras</th>
                <th scope="col">Apskritis</th>
                <th scope="col">Miestas</th>
                <th scope="col">Veiksmas</th>
              </tr>
            </thead>
            <tbody>
         @foreach ($users as $user)
              <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->specialty }}</td>
                <td>{{ $user->genre }}</td>
                <td>{{ $user->locations[0]->county ? $user->locations[0]->county : 'Nenurodyta'}}</td>
                <td>{{ $user->locations[0]->city ? $user->locations[0]->city : 'Nenurodyta'}}</td>
                <td>
                    <a href="" class="btn btn-primary">Susisiekti</a>
                </td>
              </tr>
             @endforeach
            </tbody>
          </table>
    </div>
@endsection
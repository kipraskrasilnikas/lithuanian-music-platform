<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    function login() {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        return view('login');
    }

    function registration() {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }
        
        return view('registration');
    }

    function loginPost(Request $request) {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ],
        [
            'email.required'                 => 'El. paštas yra privalomas!',
            'email.email'                    => 'Neteisingas el. pašto formatas!',
            'password.required'              => 'Slaptažodis yra privalomas!',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('home'))->with("success", "Prisijungimas sėkmingas!");
        } 
        
        return redirect()->back()->withInput()->with("error", "Prisijungimo duomenys neteisingi!");
    }

    function registrationPost(Request $request) {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed',
            'password_confirmation'  => 'required|min:8'
        ],
        [
            'name.required'                  => 'Vardas yra privalomas!',
            'email.required'                 => 'El. paštas yra privalomas!',
            'email.email'                    => 'Neteisingas el. pašto formatas!',
            'email.unique'                   => 'Toks el. paštas jau egzistuoja!',
            'password.required'              => 'Slaptažodis yra privalomas!',
            'password.min'                   => 'Slaptažodis turi būti bent 8 simbolių ilgio!',
            'password.confirmed'             => 'Slaptažodžiai turi sutapti!',
            'password_confirmation.required' => 'Slaptažodžio patvirtinimas yra privalomas!',
            'password_confirmation.min'      => 'Slaptažodžio patvirtinimo laukas turi būti bent 8 simbolių ilgio!',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        if(!$user) {
            return redirect(route('registration'))->with("error", "Registracija nepavyko. Bandykite iš naujo.");
        }

        return redirect(route('login'))->with("success", "Registraciją sėkminga! Prisijunkite, kad pasiekti platformą."); 
    }

    function logout() {
        Session::flush();
        Auth::logout();
        return redirect(route('home'));
    }
}

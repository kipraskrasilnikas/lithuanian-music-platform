<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('home'))->with("success", "Login Successful!");
        } 
        
        return redirect()->back()->withInput()->with("error", "Login details are not valid!");
    }

    function registrationPost(Request $request) {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed',
            'password_confirmation'  => 'required|min:8'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        if(!$user) {
            return redirect(route('registration'))->with("error", "Registration failed, try again.");
        }

        return redirect(route('login'))->with("success", "Registration successful, login to access the app."); 
    }

    function logout() {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }

    function profile() {
        if (Auth::check()) {
            $user = Auth::user();
            return view('profile', compact('user'));
        }

        return redirect()->route('home');
    }

    function profilePost(Request $request) {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => $request->filled('password') ? 'min:8|confirmed' : '',
            'password_confirmation'  => $request->filled('password') ? 'min:8' : ''
        ]);

        // Retrieve the authenticated user
        $user = Auth::user();

        // Update user's information based on form input
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Update password only if it's filled
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save the updated user information
        $user->save();
        
        // Redirect with success message
        return redirect()->route('profile')->with("success", "Profile updated successfully!");
    }
}

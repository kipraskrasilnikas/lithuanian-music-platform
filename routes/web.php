<?php

use App\Http\Controllers\AuthManager;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\MusicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthManager::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');

Route::get('/registration', [AuthManager::class, 'registration'])->name('registration');
Route::post('/registration', [AuthManager::class, 'registrationPost'])->name('registration.post');

Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'profilePost'])->name('profile.post');

    // create
    Route::post("/resources", [ResourceController::class, 'store'])->name('resources.store');
    Route::get("/resources/create", [ResourceController::class, 'create'])->name('resources.create');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/searchPost', [SearchController::class, 'searchPost'])->name('search.post');

// Resources CRUD
Route::get("/resources", [ResourceController::class, 'index'])->name('resources');

// read
Route::get("/resource/{id}", [ResourceController::class, 'show'])->name('resources.show');

// update
Route::get("/resource/{id}/edit", [ResourceController::class, 'edit'])->name('resources.edit');
Route::post("/resource/{id}", [ResourceController::class, 'update'])->name('resources.update');

// delete
Route::delete("resource/{id}", [ResourceController::class, 'destroy'])->name('resources.delete');

// search
Route::get('/searchResource', [SearchController::class, 'searchResource'])->name('search.resource');

Route::get('/music', [MusicController::class, 'music'])->name('music');

Route::get('/search/{id}', [ProfileController::class, 'show'])->name('search.show');
// Route::get('/songsPost', [SearchController::class, 'searchSongs'])->name('songs.post');
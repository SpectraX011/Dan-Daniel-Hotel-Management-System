<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are the map for your entire website.
|
*/

// Homepage Route
Route::get('/', function () {
    return view('home.index');
    })->name('home');


// About Us Page Route
Route::get('/about', function () {
    return view('home.about');
});

// Our Room Page Route
Route::get('/room', function () {
    return view('home.room');
});

// Gallery Page Route (Corrected to match your 'gallery.blade.php' filename)
Route::get('/gallery', function () {
    return view('home.gallery');
});

// Chronicle (Blog) Page Route
Route::get('/blog', function () {
    return view('home.blog');
});

// Contact Us Page Route
Route::get('/contact', function () {
    return view('home.contact');
});

// Admin Dashboard Route
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');


// Middleware group for authenticated users
Route::middleware([
    // 'auth:sanctum', 
    // config('jetstream.auth_session'),
    // 'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});

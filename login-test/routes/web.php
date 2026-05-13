<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(OAuthController::class)->group(function() {
    Route::get('/auth/{provider}/redirect', 'redirect')->name('oauth.redirect');
    Route::get('/auth/{provider}/callback', 'callback')->name('oauth.callback');
});

require __DIR__ . '/auth.php';

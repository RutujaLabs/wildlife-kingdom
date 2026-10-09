<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HabitatController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware('auth:web')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::resource('animals', AnimalController::class)->except(['show']);
        Route::resource('habitats', HabitatController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('gallery', GalleryController::class)->except(['show']);
    });
});

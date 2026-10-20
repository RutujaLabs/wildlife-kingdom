<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HabitatController;
use App\Http\Controllers\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/animals', [FrontendController::class, 'animals'])->name('animals');
Route::get('/animals/{animal}', [FrontendController::class, 'animalDetails'])->name('animal.show');
Route::get('/habitats', [FrontendController::class, 'habitats'])->name('habitats');
Route::get('/events', [FrontendController::class, 'events'])->name('events');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');

Route::prefix('admin')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware('auth:web')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::get('/animals/{animal}/preview', [AnimalController::class, 'preview'])->name('animals.preview');
        Route::resource('animals', AnimalController::class)->except(['show']);
        Route::resource('habitats', HabitatController::class)->except(['show']);
        Route::resource('events', EventController::class)->except(['show']);
        Route::resource('gallery', GalleryController::class)->except(['show']);
        Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/seo', [SiteSettingController::class, 'editSeo'])->name('settings.seo.edit');
        Route::put('/settings/seo', [SiteSettingController::class, 'updateSeo'])->name('settings.seo.update');
        Route::get('/settings/api', [SiteSettingController::class, 'editApi'])->name('settings.api.edit');
        Route::put('/settings/api', [SiteSettingController::class, 'updateApi'])->name('settings.api.update');
        Route::get('/settings/website', [SiteSettingController::class, 'editWebsite'])->name('settings.website.edit');
        Route::put('/settings/website', [SiteSettingController::class, 'updateWebsite'])->name('settings.website.update');
    });
});

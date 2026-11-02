<?php

use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HabitatController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/animals', [FrontendController::class, 'animals'])->name('animals');
Route::get('/animals/{animal}', [FrontendController::class, 'animalDetails'])->name('animal.show');
Route::get('/habitats', [FrontendController::class, 'habitats'])->name('habitats');
Route::get('/events', [FrontendController::class, 'events'])->name('events');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');

Route::get('/tickets', [BookingController::class, 'tickets'])->name('front.tickets.index');
Route::get('/tickets/verify/{verificationToken}', [BookingController::class, 'verify'])->name('tickets.verify');
Route::get('/tickets/{ticket}/book', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
Route::post('/bookings/{booking}/payment', [BookingController::class, 'processPayment'])->name('bookings.payment.process');
Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::get('/bookings/{booking}/ticket.pdf', [BookingController::class, 'downloadTicket'])->name('bookings.ticket.download');

Route::prefix('admin')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::middleware('auth:web')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        Route::resource('tickets', TicketController::class)->except(['show']);
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/bookings/{booking}/ticket.pdf', [AdminBookingController::class, 'downloadTicket'])->name('bookings.ticket');

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

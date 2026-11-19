<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ===== GUEST & PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{id}', [EventController::class, 'detail'])->name('event.detail');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

// ===== AUTHENTICATED ROUTES =====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/event/{id}/register', [EventController::class, 'register'])->name('event.register');

    // ===== ADMIN ROUTES =====
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');

        // Form Edit Event & Daftar Peserta
        Route::get('/event/{id}/edit', [AdminController::class, 'editEvent'])->name('event.edit');
        Route::get('/event/{id}/participants', [AdminController::class, 'eventParticipants'])->name('event.participants');
        Route::get('/event/{id}/export-csv', [AdminController::class, 'exportEventCsv'])->name('event.exportCsv');

        // Event CRUD
        Route::post('/event', [AdminController::class, 'storeEvent'])->name('event.store');
        Route::put('/event/{id}', [AdminController::class, 'updateEvent'])->name('event.update');
        Route::delete('/event/{id}', [AdminController::class, 'deleteEvent'])->name('event.delete');

        // User CRUD
        Route::post('/user', [AdminController::class, 'storeUser'])->name('user.store');
        Route::put('/user/{id}', [AdminController::class, 'updateUser'])->name('user.update');
        Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

        // Announcements
        Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements');
        Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('announcement.store');
        Route::put('/announcements/{id}', [AdminController::class, 'updateAnnouncement'])->name('announcement.update');
        Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('announcement.delete');
    });

    // ===== ORGANIZER ROUTES =====
    Route::prefix('organizer')->name('organizer.')->middleware('role:penyelenggara')->group(function () {
        Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');

        // Form Edit Event & Kelola Peserta
        Route::get('/event/{id}/edit', [OrganizerController::class, 'editEvent'])->name('event.edit');
        Route::get('/event/{id}/participants', [OrganizerController::class, 'participants'])->name('event.participants');

        // Event CRUD
        Route::post('/event', [OrganizerController::class, 'storeEvent'])->name('event.store');
        Route::put('/event/{id}', [OrganizerController::class, 'updateEvent'])->name('event.update');
        Route::delete('/event/{id}', [OrganizerController::class, 'deleteEvent'])->name('event.delete');

        // Participant Management
        Route::post('/participant/{id}/confirm', [OrganizerController::class, 'confirmParticipant'])->name('participant.confirm');
        Route::post('/participant/{id}/attend', [OrganizerController::class, 'attendParticipant'])->name('participant.attend');
        Route::post('/participant/{id}/cancel', [OrganizerController::class, 'cancelParticipant'])->name('participant.cancel');
        Route::post('/quick-checkin', [OrganizerController::class, 'quickCheckIn'])->name('quickCheckin');
        Route::get('/export-csv/{event_id?}', [OrganizerController::class, 'exportCsv'])->name('exportCsv');
    });

    // ===== MAHASISWA ROUTES =====
    Route::get('/my-events', [HomeController::class, 'myEvents'])->name('my.events');
    Route::post('/my-events/cancel/{id}', [HomeController::class, 'cancelEvent'])->name('my.events.cancel');
    Route::get('/my-events/{id}/certificate', [HomeController::class, 'certificate'])->name('my.events.certificate');
});
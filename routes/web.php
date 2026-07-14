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

// ===== GUEST ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ===== AUTHENTICATED ROUTES =====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/event/{id}', [EventController::class, 'detail'])->name('event.detail');
    Route::post('/event/{id}/register', [EventController::class, 'register'])->name('event.register');

    // ===== ADMIN ROUTES =====
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // TAMBAHKAN ROUTE UNTUK FORM EDIT (GET)
        Route::get('/event/{id}/edit', function ($id) {
            $event = \App\Models\Event::findOrFail($id);
            $categories = \App\Models\Category::all();
            $users = \App\Models\User::all();
            return view('admin.events.edit', compact('event', 'categories', 'users'));
        })->name('event.edit');
        
        // Event CRUD - PERBAIKAN: Gunakan nama route yang konsisten
        Route::post('/event', [AdminController::class, 'storeEvent'])->name('event.store');
        Route::put('/event/{id}', [AdminController::class, 'updateEvent'])->name('event.update');  // <-- ini yang dipanggil di form
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
    });

    // ===== ORGANIZER ROUTES =====
    Route::prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');
        
        // TAMBAHKAN ROUTE UNTUK FORM EDIT (GET)
        Route::get('/event/{id}/edit', function ($id) {
            $event = \App\Models\Event::where('created_by', Auth::id())->findOrFail($id);
            $categories = \App\Models\Category::all();
            return view('organizer.events.edit', compact('event', 'categories'));
        })->name('event.edit');
        
        // Event CRUD - PERBAIKAN: Gunakan nama route yang konsisten
        Route::post('/event', [OrganizerController::class, 'storeEvent'])->name('event.store');
        Route::put('/event/{id}', [OrganizerController::class, 'updateEvent'])->name('event.update');  // <-- ini yang dipanggil di form
        Route::delete('/event/{id}', [OrganizerController::class, 'deleteEvent'])->name('event.delete');
        
        // Participant Management
        Route::post('/participant/{id}/confirm', [OrganizerController::class, 'confirmParticipant'])->name('participant.confirm');
        Route::post('/participant/{id}/attend', [OrganizerController::class, 'attendParticipant'])->name('participant.attend');
    });

    // ===== MAHASISWA ROUTES =====
    Route::get('/my-events', [HomeController::class, 'myEvents'])->name('my.events');
    Route::get('/my-events/cancel/{id}', [HomeController::class, 'cancelEvent'])->name('my.events.cancel');
});

// ===== TEST ROUTE =====
Route::get('/force-login', function () {
    $user = App\Models\User::where('email', 'admin@campven.com')->first();
    if ($user) {
        auth()->login($user);
        return redirect('/admin/dashboard');
    }
    return 'User tidak ditemukan! Jalankan seeder dulu.';
});
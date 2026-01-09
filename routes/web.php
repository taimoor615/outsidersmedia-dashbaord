<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Redirect root to login
Route::get('/', function () {
    // 1. If user is logged in, send them to their dashboard
    if (auth()->check()) {
        // You can add logic here to check roles if needed
        if (auth()->user()->isAdmin()){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('team.dashboard');
        }
        // For now, let's send them to the team dashboard (or admin)
    }

    // 2. If NOT logged in, send to login
    return redirect()->route('login');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Logout Route (Authenticated Users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Future admin routes will go here
    // Route::resource('clients', ClientController::class);
    // Route::resource('team', TeamController::class);
});

// Team Routes (Both Admin and Team can access)
Route::middleware(['auth', 'team'])->prefix('team')->name('team.')->group(function () {
    Route::get('/dashboard', function () {
        return view('team.dashboard');
    })->name('dashboard');

    // Future team routes will go here
    // Route::resource('posts', PostController::class);
});

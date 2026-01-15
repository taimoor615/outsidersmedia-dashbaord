<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ProfileController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Email Verification
    Route::get('/verify-email', [VerificationController::class, 'show'])->name('verify.email');
    Route::post('/verify-email', [VerificationController::class, 'verify'])->name('verify.email.post');
});

// Logout Route (Authenticated Users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes (All authenticated users)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/image', [ProfileController::class, 'deleteImage'])->name('image.delete');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Team Management
    Route::resource('team', TeamController::class);
    Route::post('team/{team}/resend-verification', [TeamController::class, 'resendVerification'])->name('team.resend');
    Route::post('team/{team}/toggle-status', [TeamController::class, 'toggleStatus'])->name('team.toggle-status');

    // Client Management
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
});

// Team Routes (Both Admin and Team can access)
Route::middleware(['auth', 'team'])->prefix('team')->name('team.')->group(function () {
    Route::get('/dashboard', function () {
        return view('team.dashboard');
    })->name('dashboard');
});

// Post Routes (Both Admin and Team can access)
Route::middleware(['auth', 'team'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/submit-approval', [PostController::class, 'submitForApproval'])->name('posts.submit-approval');
    Route::post('posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve');
    Route::post('posts/{post}/reject', [PostController::class, 'reject'])->name('posts.reject');
    Route::delete('post-media/{media}', [PostController::class, 'deleteMedia'])->name('post-media.delete');
});

// Client Portal Routes (Public - no auth required)
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/{token}', [ClientPortalController::class, 'show'])->name('view');
    Route::post('/{token}/feedback', [ClientPortalController::class, 'submitFeedback'])->name('feedback');
    Route::post('/{token}/approve/{post}', [ClientPortalController::class, 'approvePost'])->name('approve');
    Route::post('/{token}/reject/{post}', [ClientPortalController::class, 'rejectPost'])->name('reject');
});

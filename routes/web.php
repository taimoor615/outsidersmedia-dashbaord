<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;

// Redirect root to login
Route::get('/', function () {
    // 1. If user is logged in, send them to their dashboard
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        // Assuming non-admins are team members
        return redirect()->route('team.dashboard');
    }

    // 2. If user is NOT logged in, send them to login
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

// Notifications (authenticated users)
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
});

// Profile Routes (All authenticated users)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/image', [ProfileController::class, 'deleteImage'])->name('image.delete');
});

// Admin Only Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Team Management (Admin Only)
    Route::resource('team', TeamController::class);
    Route::post('team/{team}/resend-verification', [TeamController::class, 'resendVerification'])->name('team.resend');
    Route::post('team/{team}/toggle-status', [TeamController::class, 'toggleStatus'])->name('team.toggle-status');
});

// Team Dashboard Route
Route::middleware(['auth', 'team'])->prefix('team')->name('team.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Team\DashboardController::class, 'index'])->name('dashboard');
});

// Shared Routes (Both Admin and Team can access)
Route::middleware(['auth', 'team'])->group(function () {

    // Client Management (Both Admin and Team)
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy'); // Auth check in controller
        Route::post('/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Post Management (Both Admin and Team)
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/submit-approval', [PostController::class, 'submitForApproval'])->name('posts.submit-approval');
    Route::post('posts/{post}/resubmit-to-client', [PostController::class, 'resubmitToClient'])->name('posts.resubmit-to-client');
    Route::post('posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve');
    Route::post('posts/{post}/schedule', [PostController::class, 'schedule'])->name('posts.schedule');
    Route::post('posts/{post}/return-to-client', [PostController::class, 'returnToClient'])->name('posts.return-to-client');
    Route::delete('post-media/{media}', [PostController::class, 'deleteMedia'])->name('post-media.delete');

    // Calendar (Admin: all posts; Team: own posts)
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
});

// Client Portal Routes (Public - no auth required)
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/{token}', [ClientPortalController::class, 'show'])->name('view');
    Route::post('/{token}/feedback', [ClientPortalController::class, 'submitFeedback'])->name('feedback');
    Route::post('/{token}/approve/{post}', [ClientPortalController::class, 'approvePost'])->name('approve');
    Route::post('/{token}/reject/{post}', [ClientPortalController::class, 'rejectPost'])->name('reject');
});

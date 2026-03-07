<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\TeamMiddleware;  // <--- You were missing this
use App\Http\Middleware\AdminMiddleware; // <--- You are likely missing this too!

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'team' => TeamMiddleware::class,
            'check.status' => CheckUserStatus::class,
        ]);

        // Apply globally to web routes
        $middleware->web(append: [
            CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

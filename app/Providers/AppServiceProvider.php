<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (auth()->check() && auth()->user()->isAdmin()) {
                $view->with('pending_approvals_count', Post::where('status', 'pending_approval')->count());
            }
        });

        View::composer('layouts.team', function ($view) {
            if (auth()->check() && auth()->user()->isTeam()) {
                $userId = auth()->id();
                $view->with('pending_approval_count', Post::where('created_by', $userId)->where('status', 'pending_approval')->count());
                $view->with('changes_requested_count', Post::where('created_by', $userId)->where('status', 'changes_requested')->count());
                $view->with('scheduled_count', Post::where('created_by', $userId)->where('status', 'scheduled')->count());
            }
        });
    }
}

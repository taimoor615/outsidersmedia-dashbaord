<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Post;
use App\Services\SocialPublishService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('posts:publish-scheduled', function () {
    $due = Post::where('status', 'scheduled')
        ->whereNotNull('scheduled_at')
        ->where('scheduled_at', '<=', now())
        ->get();

    $service = app(SocialPublishService::class);
    $published = 0;

    foreach ($due as $post) {
        if ($service->publish($post)) {
            $published++;
        }
    }

    if ($published > 0) {
        $this->info("Published {$published} post(s).");
    }
})->purpose('Publish posts whose scheduled_at time has passed');

Schedule::command('posts:publish-scheduled')->everyMinute();

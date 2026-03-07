<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read and redirect to the post (or URL in notification data).
     */
    public function markAsRead(Request $request, string $id)
    {
        $notification = auth()->user()->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();
        $url = $notification->data['url'] ?? route('posts.index');
        return redirect($url);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}

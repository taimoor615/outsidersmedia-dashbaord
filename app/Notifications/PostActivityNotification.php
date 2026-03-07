<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Notifications\Notification;

class PostActivityNotification extends Notification
{

    public function __construct(
        public Post $post,
        public string $type, // 'client_requested_changes', 'client_approved', 'admin_approved', 'admin_scheduled'
        public string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'post_title' => \Str::limit($this->post->facebook_message ?: $this->post->instagram_message ?: 'Post #' . $this->post->id, 40),
            'client_name' => $this->post->client->name,
            'type' => $this->type,
            'message' => $this->message,
            'url' => route('posts.show', $this->post),
        ];
    }
}

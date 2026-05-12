<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $postTitle = \Str::limit($this->post->facebook_message ?: $this->post->instagram_message ?: 'Post #' . $this->post->id, 60);
        $clientName = $this->post->client?->name ?? 'Unknown Client';
        $postUrl = route('posts.show', $this->post);

        $subjects = [
            'client_approved'           => "✅ Client Approved: {$clientName}",
            'client_requested_changes'  => "✏️ Changes Requested: {$clientName}",
            'admin_approved'            => "✅ Post Approved by Admin",
            'admin_scheduled'           => "📅 Post Scheduled: {$clientName}",
        ];

        $subject = $subjects[$this->type] ?? 'Post Activity Update';

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($this->message)
            ->line("**Post:** {$postTitle}")
            ->line("**Client:** {$clientName}")
            ->action('View Post', $postUrl)
            ->salutation('— Outsidersmedia Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'post_title' => \Str::limit($this->post->facebook_message ?: $this->post->instagram_message ?: 'Post #' . $this->post->id, 40),
            'client_name' => $this->post->client?->name ?? 'Unknown Client',
            'type' => $this->type,
            'message' => $this->message,
            'url' => route('posts.show', $this->post),
        ];
    }
}

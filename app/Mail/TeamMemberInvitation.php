<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamMemberInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $verificationUrl;
    public string $temporaryPassword;

    public function __construct(User $user, string $verificationUrl, string $temporaryPassword)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Outsidersmedia - Activate Your Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'verificationUrl' => $this->verificationUrl,
                'temporaryPassword' => $this->temporaryPassword,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

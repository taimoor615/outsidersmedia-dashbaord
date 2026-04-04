<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Client $client;
    public User $createdBy;

    public function __construct(Client $client, User $createdBy)
    {
        $this->client    = $client;
        $this->createdBy = $createdBy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Client Added: ' . $this->client->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-client-notification',
            with: [
                'clientName'   => $this->client->name,
                'clientEmail'  => $this->client->email,
                'planType'     => ucfirst($this->client->plan_type),
                'createdBy'    => $this->createdBy->name,
                'clientUrl'    => route('clients.show', $this->client),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

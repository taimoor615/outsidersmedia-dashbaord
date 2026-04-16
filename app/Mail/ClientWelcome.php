<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientWelcome extends Mailable
{
    use Queueable, SerializesModels;

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Outsidersmedia - Your Content Portal is Ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-welcome',
            with: [
                'clientName'  => $this->client->name,
                'planType'    => ucfirst($this->client->plan_type),
                'portalUrl'   => $this->client->share_url,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

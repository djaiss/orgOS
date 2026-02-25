<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvited extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $organizationName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join ' . $this->organizationName . ' on ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.user-invited-text',
            markdown: 'mail.user-invited',
            with: [
                'organizationName' => $this->organizationName,
            ],
        );
    }
}

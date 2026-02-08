<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\CreateEmailSent;
use App\Enums\EmailType;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Resend\Laravel\Facades\Resend;

class SendEmail implements ShouldQueue
{
    use Queueable;

    private string $subject;

    private ?string $uuid = null;

    /**
     * Send an email to the given user.
     * We need to use this abstraction because for our own use case in production,
     * we use Resend and all its capabilities (including webhooks), so we need
     * to capture the UUID Resend sends.
     * In any other context, the default Laravel Mail class is used, allowing
     * you to send emails the way Laravel Mail does.
     */
    public function __construct(
        public Mailable $mailable,
        public User $user,
        public EmailType $emailType,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->setSubject();

        if (config('app.use_resend')) {
            $this->sendWithResend();
        } else {
            $this->sendTheTraditionalWay();
        }

        $this->recordEmailSent();
    }

    private function setSubject(): void
    {
        $this->subject = $this->mailable->envelope()->subject;
    }

    private function sendWithResend(): void
    {
        $response = Resend::emails()->send([
            'from' => config('mail.from.address'),
            'to' => [$this->user->email],
            'subject' => $this->subject,
            'html' => $this->mailable->render(),
        ]);

        $this->uuid = $response->id;
    }

    private function sendTheTraditionalWay(): void
    {
        Mail::to($this->user->email)->send($this->mailable);
    }

    private function recordEmailSent(): void
    {
        new CreateEmailSent(
            user: $this->user,
            uuid: $this->uuid,
            emailType: $this->emailType->value,
            emailAddress: $this->user->email,
            subject: $this->subject,
            body: $this->mailable->render(),
        )->execute();
    }
}

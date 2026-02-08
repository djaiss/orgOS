<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Enums\EmailType;
use App\Jobs\SendEmail;
use App\Mail\LoginFailed;
use App\Models\EmailSent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Resend\Email;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_sends_email_the_traditional_way(): void
    {
        Config::set('app.use_resend', false);
        Config::set('app.name', 'orgOS');
        Config::set('mail.from.address', 'noreply@example.com');
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $job = new SendEmail(
            mailable: new LoginFailed,
            user: $user,
            emailType: EmailType::LOGIN_FAILED,
        );

        $job->handle();

        Mail::assertQueued(
            LoginFailed::class,
            fn (LoginFailed $mail) => $mail->hasTo($user->email),
        );

        $emailSent = EmailSent::query()->latest()->first();
        $this->assertEquals(EmailType::LOGIN_FAILED->value, $emailSent->email_type);
        $this->assertEquals('michael.scott@dundermifflin.com', $emailSent->email_address);
        $this->assertEquals('Login attempt on orgOS', $emailSent->subject);
    }

    #[Test]
    public function it_sends_email_with_resend_facade(): void
    {
        Config::set('app.use_resend', true);
        Config::set('app.name', 'orgOS');
        Config::set('mail.from.address', 'noreply@example.com');

        $resendMock = Mockery::mock();
        $emailsMock = Mockery::mock(\Resend\Service\Email::class);

        $emailsMock
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(
                fn ($args): bool => (
                    $args['from'] === 'noreply@example.com'
                    && $args['to'] === ['michael.scott@dundermifflin.com']
                    && $args['subject'] === 'Login attempt on orgOS'
                    && is_string($args['html'])
                    && mb_strlen($args['html']) > 0
                ),
            ))
            ->andReturn(Email::from(['id' => 'resend-uuid-12345']));

        // The facade accesses the emails property directly, not method
        $resendMock->emails = $emailsMock;

        // Replace the Resend service binding with our mock
        app()->instance('resend', $resendMock);

        $user = User::factory()->create([
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $job = new SendEmail(
            mailable: new LoginFailed,
            user: $user,
            emailType: EmailType::LOGIN_FAILED,
        );

        $job->handle();

        $emailSent = EmailSent::query()->latest()->first();
        $this->assertEquals(EmailType::LOGIN_FAILED->value, $emailSent->email_type);
        $this->assertEquals('michael.scott@dundermifflin.com', $emailSent->email_address);
        $this->assertEquals('Login attempt on orgOS', $emailSent->subject);
        $this->assertEquals('resend-uuid-12345', $emailSent->uuid);
    }
}

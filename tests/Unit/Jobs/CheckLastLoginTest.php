<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Enums\EmailType;
use App\Jobs\CheckLastLogin;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckLastLoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_sends_email_when_ip_address_changes(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'last_used_ip' => '192.168.1.1',
        ]);

        $job = new CheckLastLogin(
            user: $user,
            ip: '192.168.1.2',
        );

        $job->handle();

        Queue::assertPushed(
            SendEmail::class,
            fn (SendEmail $job): bool => $job->user->id === $user->id && $job->emailType === EmailType::USER_IP_CHANGED,
        );

        $this->assertEquals('192.168.1.2', $user->fresh()->last_used_ip);
    }

    #[Test]
    public function it_does_not_send_email_when_ip_address_does_not_change(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'last_used_ip' => '192.168.1.1',
        ]);

        $job = new CheckLastLogin(
            user: $user,
            ip: '192.168.1.1',
        );

        $job->handle();

        Queue::assertNotPushed(SendEmail::class);

        $this->assertEquals('192.168.1.1', $user->fresh()->last_used_ip);
    }

    #[Test]
    public function it_does_not_send_email_on_first_login(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'last_used_ip' => null,
        ]);

        $job = new CheckLastLogin(
            user: $user,
            ip: '192.168.1.1',
        );

        $job->handle();

        Queue::assertNotPushed(SendEmail::class);

        $this->assertEquals('192.168.1.1', $user->fresh()->last_used_ip);
    }
}

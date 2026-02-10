<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Auth;

use App\Enums\EmailType;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendMagicLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_request_magic_link_screen(): void
    {
        $response = $this->get('/send-magic-link');

        $response->assertStatus(200);
        $response->assertViewIs('app.auth.request-magic-link');
    }

    #[Test]
    public function it_sends_a_magic_link_for_existing_user(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $response = $this->post('/send-magic-link', [
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('app.auth.magic-link-sent');

        Queue::assertPushed(
            SendEmail::class,
            fn (SendEmail $job): bool => (
                $job->emailType === EmailType::MAGIC_LINK_CREATED
                && $job->user->id === $user->id
            ),
        );
    }

    #[Test]
    public function it_does_not_reveal_if_user_does_not_exist(): void
    {
        Queue::fake();

        $response = $this->post('/send-magic-link', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('app.auth.magic-link-sent');

        Queue::assertNotPushed(SendEmail::class);
    }
}

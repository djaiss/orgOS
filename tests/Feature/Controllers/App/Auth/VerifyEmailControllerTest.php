<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VerifyEmailControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_verifies_the_email_with_valid_link(): void
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertNotNull($user->fresh()->email_verified_at);
        Event::assertDispatched(Verified::class);
        $response->assertRedirect(route('dashboard.index', absolute: false).'?verified=1');
    }

    #[Test]
    public function it_redirects_to_dashboard_if_email_is_already_verified(): void
    {
        Event::fake();

        $user = User::factory()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertNotDispatched(Verified::class);
        $response->assertRedirect(route('dashboard.index', absolute: false).'?verified=1');
    }

    #[Test]
    public function it_rejects_invalid_verification_links(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get("/verify-email/{$user->id}/invalid-hash");

        $response->assertStatus(403);
        $this->assertNull($user->fresh()->email_verified_at);
    }
}

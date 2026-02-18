<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Auth;

use App\Jobs\CheckLastLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use PragmaRX\Google2FALaravel\Google2FA;
use Tests\TestCase;

class TwoFAChallengeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_2fa_challenge_page(): void
    {
        $user = $this->createUser();

        $response = $this->withSession(['2fa:user:id' => $user->id])
            ->get('/2fa-challenge');

        $response->assertStatus(200);
        $response->assertViewIs('app.auth.2fa');
        $response->assertViewHas('quote');
    }

    #[Test]
    public function it_authenticates_user_with_valid_totp_code(): void
    {
        Queue::fake();

        $google2fa = new Google2FA(request());
        $secret = $google2fa->generateSecretKey();

        $user = $this->createUser([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ]);

        $validCode = $google2fa->getCurrentOtp($secret);

        $response = $this->withSession(['2fa:user:id' => $user->id])
            ->post('/2fa-challenge', [
                'code' => $validCode,
            ]);

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('organization.index', absolute: false));

        $this->assertFalse(session()->has('2fa:user:id'));

        Queue::assertPushedOn(
            queue: 'low',
            job: CheckLastLogin::class,
            callback: fn (CheckLastLogin $job): bool => $job->user->id === $user->id,
        );
    }

    #[Test]
    public function it_authenticates_user_with_valid_recovery_code(): void
    {
        Queue::fake();

        $google2fa = new Google2FA(request());
        $secret = $google2fa->generateSecretKey();

        $user = $this->createUser([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => ['ABC123', 'DEF456', 'GHI789'],
        ]);

        $response = $this->withSession(['2fa:user:id' => $user->id])
            ->post('/2fa-challenge', [
                'code' => 'ABC123',
            ]);

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('organization.index', absolute: false));

        $user->refresh();
        $this->assertNotContains('ABC123', $user->two_factor_recovery_codes);
        $this->assertCount(2, $user->two_factor_recovery_codes);

        Queue::assertPushedOn(
            queue: 'low',
            job: CheckLastLogin::class,
        );
    }

    #[Test]
    public function it_rejects_invalid_code(): void
    {
        $google2fa = new Google2FA(request());
        $secret = $google2fa->generateSecretKey();

        $user = $this->createUser([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->withSession(['2fa:user:id' => $user->id])
            ->post('/2fa-challenge', [
                'code' => 'invalid-code',
            ]);

        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors(['code' => 'Invalid code']);
    }
}

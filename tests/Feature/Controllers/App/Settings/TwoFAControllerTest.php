<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PragmaRX\Google2FALaravel\Google2FA;
use Tests\TestCase;

class TwoFAControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_2fa_setup_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get('/settings/security/2fa/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.settings.security._2fa-new');
        $response->assertViewHas('secret');
        $response->assertViewHas('qrCodeSvg');
        $user->refresh();
        $this->assertNotNull($user->two_factor_secret);
    }

    #[Test]
    public function it_enables_2fa_with_valid_token(): void
    {
        $google2fa = new Google2FA(request());
        $secret = $google2fa->generateSecretKey();

        $user = $this->createUser([
            'two_factor_secret' => $secret,
        ]);

        $validToken = $google2fa->getCurrentOtp($secret);

        $response = $this->actingAs($user)
            ->from('/settings/security/2fa/create')
            ->post('/settings/security/2fa', [
                'token' => $validToken,
            ]);

        $response->assertRedirect('/settings/security');
        $response->assertSessionHas('status', 'Two-factor authentication has been enabled successfully.');

        $user->refresh();
        $this->assertNotNull($user->two_factor_confirmed_at);
        $this->assertNotNull($user->two_factor_recovery_codes);
        $this->assertCount(8, $user->two_factor_recovery_codes);
    }

    #[Test]
    public function it_rejects_invalid_token(): void
    {
        $google2fa = new Google2FA(request());
        $secret = $google2fa->generateSecretKey();

        $user = $this->createUser([
            'two_factor_secret' => $secret,
        ]);

        $response = $this->actingAs($user)
            ->from('/settings/security/2fa/create')
            ->post('/settings/security/2fa', [
                'token' => '000000',
            ]);

        $response->assertRedirect('/settings/security/2fa/create');
        $response->assertSessionHasErrors(['token' => 'The provided token is invalid.']);

        $user->refresh();
        $this->assertNull($user->two_factor_confirmed_at);
    }

    #[Test]
    public function it_removes_2fa_from_user_account(): void
    {
        $user = $this->createUser([
            'two_factor_secret' => 'test-secret',
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => ['code1', 'code2'],
        ]);

        $response = $this->actingAs($user)
            ->from('/settings/security')
            ->delete('/settings/security/2fa');

        $response->assertRedirect('/settings/security');
        $response->assertSessionHas('status', 'Changes saved');
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RecoveryCodeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_recovery_codes(): void
    {
        $user = $this->createUser([
            'two_factor_recovery_codes' => ['code1', 'code2', 'code3'],
        ]);

        $response = $this->actingAs($user)
            ->get('/settings/security/recovery-codes');

        $response->assertOk();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AutoDeleteAccountControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_enables_auto_delete_account(): void
    {
        $user = $this->createUser([
            'auto_delete_account' => false,
        ]);

        $response = $this->actingAs($user)
            ->from('/settings/security')
            ->put('/settings/security/auto-delete-account', [
                'auto_delete_account' => 'yes',
            ]);

        $response->assertRedirect('/settings/security');
        $response->assertSessionHas('status', 'Changes saved');
    }
}

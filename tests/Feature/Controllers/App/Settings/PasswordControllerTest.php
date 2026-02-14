<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_allows_the_user_to_update_their_password(): void
    {
        $user = $this->createUser([
            'password' => bcrypt('5UTHSmdj'),
        ]);

        $response = $this->actingAs($user)
            ->from('/settings/security')
            ->put('/settings/password', [
                'current_password' => '5UTHSmdj',
                'new_password' => 'new-5UTHSmdj',
                'new_password_confirmation' => 'new-5UTHSmdj',
            ]);

        $response->assertRedirect('/settings/security');
        $response->assertSessionHas('status', 'Changes saved');
    }
}

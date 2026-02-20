<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiKeyControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_new_api_token(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->from('/settings/security/create')
            ->post('/settings/api-keys', [
                'label' => 'My API Token',
            ]);

        $response->assertRedirect('/settings/security');
        $response->assertSessionHas('status', 'API key created');
    }

    #[Test]
    public function it_can_delete_an_api_token(): void
    {
        $user = $this->createUser();
        $token = $user->createToken('Test API Token');

        $response = $this->actingAs($user)
            ->delete('/settings/api-keys/' . $token->accessToken->id);

        $response->assertRedirect('/settings/security');
        $response->assertSessionHas('status', 'API key deleted');
    }
}

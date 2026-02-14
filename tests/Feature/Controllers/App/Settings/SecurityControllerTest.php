<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Settings;


use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SecurityControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_security_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get('/settings/security');

        $response->assertStatus(200);
        $response->assertViewIs('app.settings.security.index');
    }
}

<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Settings;


use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_settings_page(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/settings')
            ->assertOk()
            ->assertViewHasAll([
                'user',
            ]);
    }

    #[Test]
    public function it_updates_the_profile_information(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->put('/settings/profile', [
                'first_name' => 'Michael',
                'last_name' => 'Scott',
                'nickname' => 'Michael',
                'email' => 'michael.scott@dundermifflin.com',
                'locale' => 'en',
                'time_format_24h' => 'true',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings');
    }
}

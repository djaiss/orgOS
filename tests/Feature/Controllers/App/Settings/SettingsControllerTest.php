<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Settings;

use App\Models\Log;
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

        Log::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/settings');

        $response
            ->assertOk()
            ->assertViewHasAll([
                'user',
                'logs',
            ]);

        $response->assertViewHas(
            'logs',
            fn ($logs) => $logs->count() === 1
            && $logs->every(
                fn ($log) => isset(
                    $log->username,
                    $log->organization_name,
                    $log->organization_link,
                    $log->action,
                    $log->description,
                    $log->created_at,
                    $log->created_at_human,
                ),
            ),
        );
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

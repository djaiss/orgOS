<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Settings;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_all_the_logs(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $user = $this->createUser();

        $log = Log::factory()->create([
            'organization_id' => null,
            'user_id' => $user->id,
            'action' => 'profile_update',
            'description' => 'Updated their profile',
        ]);

        $response = $this->actingAs($user)
            ->get('/settings/logs');

        $response->assertStatus(200);
        $response->assertViewIs('app.settings.logs.index');
        $response->assertViewHas('logs');
    }

    #[Test]
    public function it_shows_a_pagination(): void
    {
        $user = $this->createUser();

        Log::factory()
            ->count(15)
            ->create([
                'organization_id' => null,
                'user_id' => $user->id,
            ]);

        $response = $this->actingAs($user)
            ->get('/settings/logs');

        $response->assertStatus(200);
        $this->assertCount(10, $response['logs']);

        $this->assertTrue($response['logs']->hasMorePages());
    }
}

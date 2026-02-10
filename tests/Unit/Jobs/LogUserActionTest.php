<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\LogUserAction;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogUserActionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_logs_user_action(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'last_activity_at' => null,
        ]);
        LogUserAction::dispatch(
            organization: null,
            user: $user,
            action: 'personal_profile_update',
            description: 'Updated their personal profile',
        );

        $log = Log::query()->first();

        $this->assertEquals('Michael Scott', $log->getUserName());
        $this->assertEquals('personal_profile_update', $log->action);
        $this->assertEquals('Updated their personal profile', $log->description);

        $this->assertEqualsWithDelta(
            now()->timestamp,
            $user->refresh()->last_activity_at->timestamp,
            1,
        );
    }
}

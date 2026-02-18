<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\Remove2fa;
use App\Jobs\LogUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Remove2faTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_removes_2fa_from_user_account(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'two_factor_secret' => 'test-secret',
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => ['code1', 'code2'],
        ]);

        new Remove2fa(
            user: $user,
        )->execute();

        $user->refresh();

        $this->assertNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_confirmed_at);
        $this->assertNull($user->two_factor_recovery_codes);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn (LogUserAction $job): bool => (
                $job->action === '2fa_removal'
                && $job->user->id === $user->id
                && $job->description === 'Removed 2FA from account'
            ),
        );
    }
}

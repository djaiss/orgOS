<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\ToggleAutoDeleteAccount;
use App\Jobs\LogUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ToggleAutoDeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_enables_auto_delete_account(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'auto_delete_account' => false,
        ]);

        $updatedUser = new ToggleAutoDeleteAccount(
            user: $user,
            autoDeleteAccount: true,
        )->execute();

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertTrue($updatedUser->auto_delete_account);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn (LogUserAction $job): bool => (
                $job->action === 'auto_delete_account_update'
                && $job->user->id === $user->id
                && $job->description === 'Updated auto delete account setting to enabled'
            ),
        );
    }
}

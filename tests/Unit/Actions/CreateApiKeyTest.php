<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Models\Organization;
use App\Actions\CreateApiKey;
use App\Enums\EmailType;
use App\Jobs\LogUserAction;
use App\Jobs\SendEmail;
use App\Mail\ApiKeyCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateApiKeyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_api_key(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $token = new CreateApiKey(
            user: $user,
            label: 'Production API Key',
        )->execute();

        $this->assertIsString($token);
        $this->assertNotEmpty($token);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'Production API Key',
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'api_key_creation'
                && $job->user->id === $user->id
                && $job->description === 'Created an API key'
                && !$job->organization instanceof Organization
            ),
        );

        Queue::assertPushedOn(
            queue: 'high',
            job: SendEmail::class,
            callback: fn(SendEmail $job): bool => (
                $job->mailable instanceof ApiKeyCreated
                && $job->mailable->label === 'Production API Key'
                && $job->user->id === $user->id
                && $job->emailType === EmailType::ApiCreated
            ),
        );
    }
}

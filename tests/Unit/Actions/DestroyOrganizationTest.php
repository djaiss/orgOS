<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\DestroyOrganization;
use App\Jobs\LogUserAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DestroyOrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_an_organization(): void
    {
        Queue::fake();
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        new DestroyOrganization(
            user: $user,
            organization: $organization,
        )->execute();

        $this->assertDatabaseMissing('organizations', [
            'id' => $organization->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => $job->action === 'organization_deletion' && $job->user->id === $user->id,
        );
    }

    #[Test]
    public function it_throws_an_exception_if_organization_does_not_belong_to_user(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = User::factory()->create();
        $otherOrganization = Organization::factory()->create();

        new DestroyOrganization(
            user: $user,
            organization: $otherOrganization,
        )->execute();
    }
}

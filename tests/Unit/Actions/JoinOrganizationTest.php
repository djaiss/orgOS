<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\JoinOrganization;
use App\Jobs\LogUserAction;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JoinOrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_joins_an_organization(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = Organization::factory()->create([
            'invitation_code' => 'ABC123',
        ]);

        $result = new JoinOrganization(
            user: $user,
            invitationCode: 'ABC123',
        )->execute();

        $this->assertInstanceOf(Organization::class, $result);
        $this->assertEquals($organization->id, $result->id);

        $this->assertDatabaseHas('members', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'organization_joined'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
            ),
        );
    }

    #[Test]
    public function it_rejects_an_invalid_invitation_code(): void
    {
        $user = $this->createUser();

        $this->expectException(ValidationException::class);

        new JoinOrganization(
            user: $user,
            invitationCode: 'INVALID',
        )->execute();
    }

    #[Test]
    public function it_rejects_if_user_is_already_a_member(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $organization->update(['invitation_code' => 'ABC123']);

        $this->expectException(ValidationException::class);

        new JoinOrganization(
            user: $user,
            invitationCode: 'ABC123',
        )->execute();
    }
}

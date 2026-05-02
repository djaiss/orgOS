<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateMemberType;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\MemberType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateMemberTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_member_type(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $memberType = new CreateMemberType(
            user: $user,
            organization: $organization,
            name: 'Employee',
        )->execute();

        $this->assertInstanceOf(MemberType::class, $memberType);

        $this->assertDatabaseHas('member_types', [
            'organization_id' => $organization->id,
            'name' => 'Employee',
            'position' => 0,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'member_type_creation'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Created a member type called Employee'
            ),
        );
    }

    #[Test]
    public function it_creates_a_member_type_when_user_is_admin(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Admin,
        );

        $memberType = new CreateMemberType(
            user: $user,
            organization: $organization,
            name: 'Contractor',
        )->execute();

        $this->assertDatabaseHas('member_types', [
            'id' => $memberType->id,
            'organization_id' => $organization->id,
            'name' => 'Contractor',
        ]);
    }

    #[Test]
    public function it_appends_to_the_end_when_no_position_is_given(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 1]);

        $memberType = new CreateMemberType(
            user: $user,
            organization: $organization,
            name: 'Third Type',
        )->execute();

        $this->assertEquals(2, $memberType->position);
    }

    #[Test]
    public function it_inserts_at_given_position_and_shifts_existing_items_down(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $first = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $second = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $third = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 2]);

        $inserted = new CreateMemberType(
            user: $user,
            organization: $organization,
            name: 'Inserted',
            position: 1,
        )->execute();

        $this->assertEquals(1, $inserted->position);
        $this->assertEquals(0, $first->fresh()->position);
        $this->assertEquals(2, $second->fresh()->position);
        $this->assertEquals(3, $third->fresh()->position);
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_not_part_of_the_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $otherOrganization = $this->addOrganization($this->createUser());

        new CreateMemberType(
            user: $user,
            organization: $otherOrganization,
            name: 'Employee',
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_a_member(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Member,
        );

        new CreateMemberType(
            user: $user,
            organization: $organization,
            name: 'Employee',
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_a_guest(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Guest,
        );

        new CreateMemberType(
            user: $user,
            organization: $organization,
            name: 'Employee',
        )->execute();
    }
}

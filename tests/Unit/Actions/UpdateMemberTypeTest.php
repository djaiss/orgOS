<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\UpdateMemberType;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\MemberType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateMemberTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_a_member_type(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Name',
        ]);

        $updated = new UpdateMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
            name: 'New Name',
        )->execute();

        $this->assertEquals('New Name', $updated->name);

        $this->assertDatabaseHas('member_types', [
            'id' => $memberType->id,
            'name' => 'New Name',
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'member_type_update'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Updated a member type called New Name'
            ),
        );
    }

    #[Test]
    public function it_moves_to_a_lower_position_and_shifts_items_down(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $a = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $b = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $c = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 2]);
        $d = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 3]);

        new UpdateMemberType(
            user: $user,
            organization: $organization,
            memberType: $d,
            name: $d->name,
            position: 1,
        )->execute();

        $this->assertEquals(0, $a->fresh()->position);
        $this->assertEquals(2, $b->fresh()->position);
        $this->assertEquals(3, $c->fresh()->position);
        $this->assertEquals(1, $d->fresh()->position);
    }

    #[Test]
    public function it_moves_to_a_higher_position_and_shifts_items_up(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $a = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $b = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $c = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 2]);
        $d = MemberType::factory()->create(['organization_id' => $organization->id, 'position' => 3]);

        new UpdateMemberType(
            user: $user,
            organization: $organization,
            memberType: $a,
            name: $a->name,
            position: 2,
        )->execute();

        $this->assertEquals(2, $a->fresh()->position);
        $this->assertEquals(0, $b->fresh()->position);
        $this->assertEquals(1, $c->fresh()->position);
        $this->assertEquals(3, $d->fresh()->position);
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_not_part_of_the_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $otherOrganization = $this->addOrganization($this->createUser());
        $memberType = MemberType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new UpdateMemberType(
            user: $user,
            organization: $otherOrganization,
            memberType: $memberType,
            name: 'New Name',
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
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        new UpdateMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
            name: 'New Name',
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
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        new UpdateMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
            name: 'New Name',
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_member_type_does_not_belong_to_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $otherOrganization = $this->addOrganization($this->createUser());
        $memberType = MemberType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new UpdateMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
            name: 'New Name',
        )->execute();
    }
}

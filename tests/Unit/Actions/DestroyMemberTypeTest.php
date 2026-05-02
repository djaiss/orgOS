<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\DestroyMemberType;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\MemberType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DestroyMemberTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_a_member_type(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Employee',
        ]);

        new DestroyMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
        )->execute();

        $this->assertDatabaseMissing('member_types', [
            'id' => $memberType->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'member_type_deletion'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Deleted a member type called Employee'
            ),
        );
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

        new DestroyMemberType(
            user: $user,
            organization: $otherOrganization,
            memberType: $memberType,
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

        new DestroyMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
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

        new DestroyMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
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

        new DestroyMemberType(
            user: $user,
            organization: $organization,
            memberType: $memberType,
        )->execute();
    }
}

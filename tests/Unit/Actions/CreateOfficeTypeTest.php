<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateOfficeType;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\OfficeType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateOfficeTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_office_type(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $officeType = new CreateOfficeType(
            user: $user,
            organization: $organization,
            name: 'Main Office',
        )->execute();

        $this->assertInstanceOf(OfficeType::class, $officeType);

        $this->assertDatabaseHas('office_types', [
            'organization_id' => $organization->id,
            'name' => 'Main Office',
            'position' => 0,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'office_type_creation'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Created an office type called Main Office'
            ),
        );
    }

    #[Test]
    public function it_creates_an_office_type_when_user_is_admin(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Admin,
        );

        $officeType = new CreateOfficeType(
            user: $user,
            organization: $organization,
            name: 'Admin Office Type',
        )->execute();

        $this->assertDatabaseHas('office_types', [
            'id' => $officeType->id,
            'organization_id' => $organization->id,
            'name' => 'Admin Office Type',
        ]);
    }

    #[Test]
    public function it_appends_to_the_end_when_no_position_is_given(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        OfficeType::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        OfficeType::factory()->create(['organization_id' => $organization->id, 'position' => 1]);

        $officeType = new CreateOfficeType(
            user: $user,
            organization: $organization,
            name: 'Third Type',
        )->execute();

        $this->assertEquals(2, $officeType->position);
    }

    #[Test]
    public function it_inserts_at_given_position_and_shifts_existing_items_down(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $first = OfficeType::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $second = OfficeType::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $third = OfficeType::factory()->create(['organization_id' => $organization->id, 'position' => 2]);

        $inserted = new CreateOfficeType(
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

        new CreateOfficeType(
            user: $user,
            organization: $otherOrganization,
            name: 'Main Office',
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

        new CreateOfficeType(
            user: $user,
            organization: $organization,
            name: 'Main Office',
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

        new CreateOfficeType(
            user: $user,
            organization: $organization,
            name: 'Main Office',
        )->execute();
    }
}

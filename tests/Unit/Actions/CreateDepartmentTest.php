<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateDepartment;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateDepartmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_department(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $department = new CreateDepartment(
            user: $user,
            organization: $organization,
            name: 'Engineering',
        )->execute();

        $this->assertInstanceOf(Department::class, $department);

        $this->assertDatabaseHas('departments', [
            'organization_id' => $organization->id,
            'name' => 'Engineering',
            'position' => 0,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'department_creation'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Created a department called Engineering'
            ),
        );
    }

    #[Test]
    public function it_creates_a_department_when_user_is_admin(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Admin,
        );

        $department = new CreateDepartment(
            user: $user,
            organization: $organization,
            name: 'Marketing',
        )->execute();

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'organization_id' => $organization->id,
            'name' => 'Marketing',
        ]);
    }

    #[Test]
    public function it_appends_to_the_end_when_no_position_is_given(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        Department::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        Department::factory()->create(['organization_id' => $organization->id, 'position' => 1]);

        $department = new CreateDepartment(
            user: $user,
            organization: $organization,
            name: 'Third Dept',
        )->execute();

        $this->assertEquals(2, $department->position);
    }

    #[Test]
    public function it_inserts_at_given_position_and_shifts_existing_items_down(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $first = Department::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $second = Department::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $third = Department::factory()->create(['organization_id' => $organization->id, 'position' => 2]);

        $inserted = new CreateDepartment(
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

        new CreateDepartment(
            user: $user,
            organization: $otherOrganization,
            name: 'Engineering',
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

        new CreateDepartment(
            user: $user,
            organization: $organization,
            name: 'Engineering',
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

        new CreateDepartment(
            user: $user,
            organization: $organization,
            name: 'Engineering',
        )->execute();
    }
}

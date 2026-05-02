<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\UpdateDepartment;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateDepartmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_a_department(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Name',
        ]);

        $updated = new UpdateDepartment(
            user: $user,
            organization: $organization,
            department: $department,
            name: 'New Name',
        )->execute();

        $this->assertEquals('New Name', $updated->name);

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'New Name',
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'department_update'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Updated a department called New Name'
            ),
        );
    }

    #[Test]
    public function it_moves_to_a_lower_position_and_shifts_items_down(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $a = Department::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $b = Department::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $c = Department::factory()->create(['organization_id' => $organization->id, 'position' => 2]);
        $d = Department::factory()->create(['organization_id' => $organization->id, 'position' => 3]);

        new UpdateDepartment(
            user: $user,
            organization: $organization,
            department: $d,
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

        $a = Department::factory()->create(['organization_id' => $organization->id, 'position' => 0]);
        $b = Department::factory()->create(['organization_id' => $organization->id, 'position' => 1]);
        $c = Department::factory()->create(['organization_id' => $organization->id, 'position' => 2]);
        $d = Department::factory()->create(['organization_id' => $organization->id, 'position' => 3]);

        new UpdateDepartment(
            user: $user,
            organization: $organization,
            department: $a,
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
        $department = Department::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new UpdateDepartment(
            user: $user,
            organization: $otherOrganization,
            department: $department,
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
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        new UpdateDepartment(
            user: $user,
            organization: $organization,
            department: $department,
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
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        new UpdateDepartment(
            user: $user,
            organization: $organization,
            department: $department,
            name: 'New Name',
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_department_does_not_belong_to_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $otherOrganization = $this->addOrganization($this->createUser());
        $department = Department::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new UpdateDepartment(
            user: $user,
            organization: $organization,
            department: $department,
            name: 'New Name',
        )->execute();
    }
}

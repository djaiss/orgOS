<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\DestroyDepartment;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DestroyDepartmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_a_department(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Engineering',
        ]);

        new DestroyDepartment(
            user: $user,
            organization: $organization,
            department: $department,
        )->execute();

        $this->assertDatabaseMissing('departments', [
            'id' => $department->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'department_deletion'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Deleted a department called Engineering'
            ),
        );
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

        new DestroyDepartment(
            user: $user,
            organization: $otherOrganization,
            department: $department,
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

        new DestroyDepartment(
            user: $user,
            organization: $organization,
            department: $department,
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

        new DestroyDepartment(
            user: $user,
            organization: $organization,
            department: $department,
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

        new DestroyDepartment(
            user: $user,
            organization: $organization,
            department: $department,
        )->execute();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\DestroyOfficeType;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\OfficeType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DestroyOfficeTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_an_office_type(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Main Office',
        ]);

        new DestroyOfficeType(
            user: $user,
            organization: $organization,
            officeType: $officeType,
        )->execute();

        $this->assertDatabaseMissing('office_types', [
            'id' => $officeType->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'office_type_deletion'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Deleted an office type called Main Office'
            ),
        );
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_not_part_of_the_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $otherOrganization = $this->addOrganization($this->createUser());
        $officeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new DestroyOfficeType(
            user: $user,
            organization: $otherOrganization,
            officeType: $officeType,
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
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        new DestroyOfficeType(
            user: $user,
            organization: $organization,
            officeType: $officeType,
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
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        new DestroyOfficeType(
            user: $user,
            organization: $organization,
            officeType: $officeType,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_office_type_does_not_belong_to_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $otherOrganization = $this->addOrganization($this->createUser());
        $officeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new DestroyOfficeType(
            user: $user,
            organization: $organization,
            officeType: $officeType,
        )->execute();
    }
}

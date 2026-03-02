<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\DestroyOffice;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\Office;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DestroyOfficeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_an_office(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
            'name' => 'Main Office',
        ]);

        new DestroyOffice(
            user: $user,
            organization: $organization,
            office: $office,
        )->execute();

        $this->assertDatabaseMissing('offices', [
            'id' => $office->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'office_deletion'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Deleted an office called Main Office'
            ),
        );
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_not_part_of_the_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new DestroyOffice(
            user: $user,
            organization: $organization,
            office: $office,
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
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new DestroyOffice(
            user: $user,
            organization: $organization,
            office: $office,
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
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new DestroyOffice(
            user: $user,
            organization: $organization,
            office: $office,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_office_does_not_belong_to_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $otherOrganization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $otherOrganization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new DestroyOffice(
            user: $user,
            organization: $organization,
            office: $office,
        )->execute();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateOrganization;
use App\Jobs\LogUserAction;
use App\Jobs\PopulateOrganization;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_organization(): void
    {
        Queue::fake();

        $user = $this->createUser();

        $organization = new CreateOrganization(
            user: $user,
            name: 'Dunder Mifflin',
        )->execute();

        $expectedSlug = $organization->id . '-dunder-mifflin';

        $this->assertInstanceOf(Organization::class, $organization);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'Dunder Mifflin',
            'slug' => $expectedSlug,
        ]);

        $this->assertDatabaseHas('members', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'organization_creation'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Created an organization called Dunder Mifflin'
            ),
        );

        Queue::assertPushedOn(
            queue: 'low',
            job: PopulateOrganization::class,
            callback: fn(PopulateOrganization $job): bool => $job->organization->id === $organization->id,
        );
    }

    #[Test]
    public function it_rejects_organization_names_with_special_characters(): void
    {
        $user = $this->createUser();

        $this->expectException(ValidationException::class);

        new CreateOrganization(
            user: $user,
            name: 'Dunder & Mifflin',
        )->execute();
    }
}

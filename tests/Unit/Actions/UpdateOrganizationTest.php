<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\UpdateOrganization;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $updatedOrganization = new UpdateOrganization(
            user: $user,
            organization: $organization,
            name: 'Threat Level Midnight',
        )->execute();

        $this->assertEquals('Threat Level Midnight', $updatedOrganization->name);
        $this->assertEquals($organization->id . '-threat-level-midnight', $updatedOrganization->slug);
    }

    #[Test]
    public function it_throws_an_exception_if_name_contains_special_characters(): void
    {
        $this->expectException(ValidationException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        new UpdateOrganization(
            user: $user,
            organization: $organization,
            name: 'Dunder@ / Mifflin!',
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_organization_does_not_belong_to_user(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $otherOrganization = Organization::factory()->create();

        new UpdateOrganization(
            user: $user,
            organization: $otherOrganization,
            name: 'Valid Organization Name',
        )->execute();
    }
}

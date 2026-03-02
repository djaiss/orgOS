<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\PopulateOrganization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PopulateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_default_office_types(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        new PopulateOrganization($organization)->handle();

        $this->assertDatabaseHas('office_types', ['organization_id' => $organization->id, 'name' => 'Headquarters', 'position' => 0]);
        $this->assertDatabaseHas('office_types', ['organization_id' => $organization->id, 'name' => 'Office', 'position' => 1]);
        $this->assertDatabaseHas('office_types', ['organization_id' => $organization->id, 'name' => 'Remote', 'position' => 2]);
        $this->assertDatabaseHas('office_types', ['organization_id' => $organization->id, 'name' => 'Coworking', 'position' => 3]);
        $this->assertDatabaseHas('office_types', ['organization_id' => $organization->id, 'name' => 'Other', 'position' => 4]);

        $this->assertEquals(5, $organization->officeTypes()->count());
    }
}

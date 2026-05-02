<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Department;
use App\Models\Member;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\MemberType;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_memberships(): void
    {
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($organization->members()->exists());
    }

    #[Test]
    public function it_has_many_offices(): void
    {
        $organization = Organization::factory()->create();
        Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($organization->offices()->exists());
    }

    #[Test]
    public function it_has_many_office_types(): void
    {
        $organization = Organization::factory()->create();
        OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($organization->officeTypes()->exists());
    }

    #[Test]
    public function it_has_many_member_types(): void
    {
        $organization = Organization::factory()->create();
        MemberType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($organization->memberTypes()->exists());
    }

    #[Test]
    public function it_has_many_departments(): void
    {
        $organization = Organization::factory()->create();
        Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($organization->departments()->exists());
    }

    #[Test]
    public function it_gets_avatar(): void
    {
        $organization = Organization::factory()->create();

        $avatar = $organization->getAvatar();

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $avatar);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Member;
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
    public function it_gets_avatar(): void
    {
        $organization = Organization::factory()->create();

        $avatar = $organization->getAvatar();

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $avatar);
    }
}

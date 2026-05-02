<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\Permission;
use App\Models\Member;
use App\Models\MemberType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_user(): void
    {
        $member = Member::factory()->create();

        $this->assertTrue($member->user()->exists());
    }

    #[Test]
    public function it_belongs_to_an_organization(): void
    {
        $member = Member::factory()->create();

        $this->assertTrue($member->organization()->exists());
    }

    #[Test]
    public function it_returns_true_when_member_is_an_administrator(): void
    {
        $member = Member::factory()->create([
            'permission' => Permission::Admin,
        ]);

        $this->assertTrue($member->isAdministrator());
    }

    #[Test]
    public function it_returns_true_when_member_is_an_owner(): void
    {
        $member = Member::factory()->create([
            'permission' => Permission::Owner,
        ]);

        $this->assertTrue($member->isOwner());
    }

    #[Test]
    public function it_belongs_to_a_member_type(): void
    {
        $memberType = MemberType::factory()->create();
        $member = Member::factory()->create([
            'organization_id' => $memberType->organization_id,
            'member_type_id' => $memberType->id,
        ]);

        $this->assertTrue($member->memberType()->exists());
    }
}

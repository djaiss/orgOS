<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\EmailSent;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_emails_sent(): void
    {
        $user = $this->createUser();
        EmailSent::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->emailsSent()->exists());
    }

    #[Test]
    public function it_has_many_memberships(): void
    {
        $user = $this->createUser();
        Member::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->memberships()->exists());
    }

    #[Test]
    public function it_has_many_organizations_through_memberships(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($user->organizations()->exists());
    }

    #[Test]
    public function it_gets_the_initials(): void
    {
        $dwight = User::factory()->create([
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
        ]);

        $this->assertEquals('DS', $dwight->initials());
    }

    #[Test]
    public function it_gets_the_full_name(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
        ]);

        $this->assertEquals('Dwight Schrute', $user->getFullName());
    }

    #[Test]
    public function it_checks_if_user_is_part_of_organization(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        $this->assertFalse($user->isPartOfOrganization($organization));

        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
        ]);
        $this->assertTrue($user->isPartOfOrganization($organization));
    }

    #[Test]
    public function it_gets_the_member_object_for_the_given_user(): void
    {
        $dwight = Member::factory()->create([]);

        $this->assertInstanceOf(
            Member::class,
            $dwight->user->memberOf($dwight->organization),
        );
    }

    #[Test]
    public function it_fails_to_get_the_member_object_if_user_is_not_part_of_the_organization(): void
    {
        $dwight = Member::factory()->create([]);
        $organization = Organization::factory()->create([]);

        $this->assertNull(
            $dwight->user->memberOf($organization),
        );
    }
}

<?php

declare(strict_types = 1);

namespace Tests\Unit\Models;

use App\Models\EmailSent;
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
    public function it_belongs_to_many_organizations(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        $user->organizations()->attach($organization->id, [
            'joined_at' => now(),
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

        $user->organizations()->attach($organization->id, [
            'joined_at' => now(),
        ]);
        $this->assertTrue($user->isPartOfOrganization($organization));
    }
}

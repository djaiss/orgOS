<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_the_members_of_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherUser = $this->createUser();
        Member::factory()->create([
            'organization_id' => $organization->id,
            'user_id' => $otherUser->id,
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/organizations/' . $organization->slug . '/adminland/members');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.members.index');
        $response->assertViewHas(
            'members',
            fn($members): bool => $members->count() === 2,
        );
    }
}

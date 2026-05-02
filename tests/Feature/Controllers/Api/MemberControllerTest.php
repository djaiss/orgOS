<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Models\Member;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $jsonStructure = [
        'data' => [
            'type',
            'id',
            'attributes' => [
                'user_id',
                'name',
                'email',
                'permission',
                'timezone',
                'birthdate',
                'joined_at',
                'created_at',
                'updated_at',
            ],
            'links' => [
                'self',
            ],
        ],
    ];

    #[Test]
    public function it_lists_members_for_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Member::factory()->create([
            'organization_id' => $organization->id,
        ]);
        Member::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/members');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonStructure['data'],
            ],
        ]);
        // addOrganization creates one member, plus 2 more = 3
        $response->assertJsonCount(3, 'data');
    }

    #[Test]
    public function it_returns_only_members_belonging_to_the_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        // Create a member in a different organization — should not appear
        Member::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/members');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    #[Test]
    public function it_restricts_listing_members_to_organization_members(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/members');

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_show_a_member(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $member = Member::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/members/' . $member->id);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_returns_404_when_showing_a_member_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $otherMember = Member::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/members/' . $otherMember->id);

        $response->assertStatus(404);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Enums\Permission;
use App\Models\Member;
use App\Models\OfficeType;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OfficeTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $jsonStructure = [
        'data' => [
            'type',
            'id',
            'attributes' => [
                'name',
                'position',
                'created_at',
                'updated_at',
            ],
            'links' => [
                'self',
            ],
        ],
    ];

    #[Test]
    public function it_lists_office_types_for_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        OfficeType::factory()->create([
            'organization_id' => $organization->id,
            'position' => 0,
        ]);
        OfficeType::factory()->create([
            'organization_id' => $organization->id,
            'position' => 1,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/officetypes');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonStructure['data'],
            ],
        ]);
        $response->assertJsonCount(2, 'data');
    }

    #[Test]
    public function it_returns_empty_collection_when_no_office_types_exist(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/officetypes');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_can_create_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/officetypes', [
            'name' => 'Remote',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_requires_a_name_when_creating_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/officetypes', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_create_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/officetypes', [
            'name' => 'Remote',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_restricts_listing_office_types_to_organization_members(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/officetypes');

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_show_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $officeType->id);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_returns_404_when_showing_an_office_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $otherOfficeType = OfficeType::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $otherOfficeType->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $officeType->id, [
            'name' => 'Remote',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_requires_a_name_when_updating_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $officeType->id, []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_destroy_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $officeType->id);

        $response->assertNoContent();
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_update_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $officeType->id, [
            'name' => 'Remote',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_destroy_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id . '/adminland/officetypes/' . $officeType->id);

        $response->assertStatus(404);
    }
}

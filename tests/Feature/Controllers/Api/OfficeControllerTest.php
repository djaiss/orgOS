<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Enums\Permission;
use App\Models\Member;
use App\Models\Office;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OfficeControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $jsonStructure = [
        'data' => [
            'type',
            'id',
            'attributes' => [
                'name',
                'address_line_1',
                'address_line_2',
                'city',
                'state_province',
                'postal_code',
                'timezone',
                'country_id',
                'office_type_id',
                'created_at',
                'updated_at',
            ],
            'links' => [
                'self',
            ],
        ],
    ];

    #[Test]
    public function it_lists_offices_for_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Office::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Alpha Office',
        ]);
        Office::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Beta Office',
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/offices');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonStructure['data'],
            ],
        ]);
        $response->assertJsonCount(2, 'data');
    }

    #[Test]
    public function it_returns_empty_collection_when_no_offices_exist(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/offices');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_restricts_listing_offices_to_organization_members(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/offices');

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_show_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/offices/' . $office->id);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_returns_404_when_showing_an_office_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $otherOffice = Office::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/offices/' . $otherOffice->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_create_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/offices', [
            'name' => 'Scranton Branch',
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_requires_a_name_when_creating_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/offices', [
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_requires_an_address_when_creating_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/offices', [
            'name' => 'Scranton Branch',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_requires_a_city_when_creating_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/offices', [
            'name' => 'Scranton Branch',
            'address_line_1' => '1725 Slough Avenue',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_create_an_office(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/offices', [
            'name' => 'Scranton Branch',
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/offices/' . $office->id, [
            'name' => 'Scranton Branch Updated',
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_requires_a_name_when_updating_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/offices/' . $office->id, [
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_update_an_office(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/offices/' . $office->id, [
            'name' => 'Scranton Branch',
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_destroy_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id . '/adminland/offices/' . $office->id);

        $response->assertNoContent();
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_destroy_an_office(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id . '/adminland/offices/' . $office->id);

        $response->assertStatus(404);
    }
}

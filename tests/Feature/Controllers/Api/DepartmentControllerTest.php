<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use App\Enums\Permission;
use App\Models\Department;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepartmentControllerTest extends TestCase
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
    public function it_lists_departments_for_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Department::factory()->create([
            'organization_id' => $organization->id,
            'position' => 0,
        ]);
        Department::factory()->create([
            'organization_id' => $organization->id,
            'position' => 1,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/departments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonStructure['data'],
            ],
        ]);
        $response->assertJsonCount(2, 'data');
    }

    #[Test]
    public function it_returns_empty_collection_when_no_departments_exist(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/departments');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_restricts_listing_departments_to_organization_members(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/departments');

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_show_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/departments/' . $department->id);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_returns_404_when_showing_a_department_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $otherDepartment = Department::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id . '/adminland/departments/' . $otherDepartment->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_create_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/departments', [
            'name' => 'Engineering',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_requires_a_name_when_creating_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/departments', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_create_a_department(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations/' . $organization->id . '/adminland/departments', [
            'name' => 'Engineering',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/departments/' . $department->id, [
            'name' => 'Product',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_requires_a_name_when_updating_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/departments/' . $department->id, []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_update_a_department(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id . '/adminland/departments/' . $department->id, [
            'name' => 'Product',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_destroy_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id . '/adminland/departments/' . $department->id);

        $response->assertNoContent();
    }

    #[Test]
    public function it_returns_404_when_a_non_admin_tries_to_destroy_a_department(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => Permission::Member,
        ]);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id . '/adminland/departments/' . $department->id);

        $response->assertStatus(404);
    }
}

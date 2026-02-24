<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use Illuminate\Support\Facades\Date;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $jsonStructure = [
        'data' => [
            'type',
            'id',
            'attributes' => [
                'name',
                'slug',
                'avatar',
                'created_at',
                'updated_at',
            ],
            'links' => [
                'self',
            ],
        ],
    ];

    #[Test]
    public function it_lists_the_organizations_of_the_current_user(): void
    {
        $user = $this->createUser();

        $this->addOrganization($user, 'Dunder Mifflin');
        $this->addOrganization($user, 'Vance refrigeration');

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->jsonStructure['data'],
            ],
        ]);
        $response->assertJsonCount(2, 'data');
    }

    #[Test]
    public function it_returns_empty_collection_when_user_has_no_organizations(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['*' => $this->jsonStructure['data']]]);
        $response->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_can_create_a_new_organization(): void
    {
        Date::setTestNow('2026-02-24 14:19:37');
        $user = $this->createUser();

        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/organizations', [
            'name' => 'Dunder Mifflin',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_can_show_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_restricts_access_to_an_organization(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/organizations/' . $organization->id);

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_update_the_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('PUT', '/api/organizations/' . $organization->id, [
            'name' => 'Dunder Mifflin Michael Scott Edition',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure($this->jsonStructure);
    }

    #[Test]
    public function it_can_delete_an_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        Sanctum::actingAs($user);

        $response = $this->json('DELETE', '/api/organizations/' . $organization->id);

        $response->assertNoContent();
    }
}

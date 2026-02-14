<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Organization;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_list_of_organizations(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create([
            'name' => 'Dunder Mifflin',
        ]);
        $user->organizations()->attach($organization, ['joined_at' => now()]);

        $response = $this->actingAs($user)->get('/organizations');

        $response->assertStatus(200);
        $response->assertViewHas(
            'organizations',
            fn ($organizations) => $organizations->count() === 1
            && $organizations->every(fn ($org) => isset($org->name, $org->link, $org->avatar)),
        );
    }

    #[Test]
    public function it_shows_the_create_organization_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/organizations/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.create');
    }

    #[Test]
    public function it_creates_an_organization(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/organizations', [
            'organization_name' => 'My Organization',
        ]);

        $this->assertDatabaseHas('organizations', [
            'name' => 'My Organization',
        ]);

        $organization = Organization::where('name', 'My Organization')->first();
        $response->assertRedirect(route('organization.show', $organization->slug));
        $response->assertSessionHas('status', 'Organization created successfully');
    }

    #[Test]
    public function it_shows_a_single_organization(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create([
            'name' => 'Dunder Mifflin',
        ]);
        $user->organizations()->attach($organization, ['joined_at' => now()]);

        $response = $this->actingAs($user)->get(route('organization.show', $organization->slug));

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.show');
        $response->assertViewHas('organization');
    }
}

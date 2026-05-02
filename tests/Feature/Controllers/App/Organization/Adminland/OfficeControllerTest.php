<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

use App\Models\Country;
use App\Models\Office;
use App\Models\OfficeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OfficeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_offices_index_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Headquarters',
            'position' => 0,
        ]);

        $country = Country::factory()->create([
            'name' => 'United States',
        ]);

        Office::factory()->create([
            'organization_id' => $organization->id,
            'office_type_id' => $officeType->id,
            'country_id' => $country->id,
            'name' => 'Main Office',
            'address_line_1' => '1725 Slough Avenue',
            'city' => 'Scranton',
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/offices');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.offices.index');
        $response->assertViewHas(
            'officeTypes',
            fn($officeTypes): bool => $officeTypes->count() === 1
                && $officeTypes->first()->name === 'Headquarters',
        );
        $response->assertViewHas(
            'offices',
            fn($offices): bool => $offices->count() === 1
                && $offices->first()->name === 'Main Office'
                && $offices->first()->office_type === 'Headquarters'
                && str_contains((string) $offices->first()->address, '1725 Slough Avenue'),
        );
        $response->assertViewHas(
            'countries',
            fn($countries): bool => $countries->contains(
                fn($entry): bool => $entry->name === 'United States',
            ),
        );
    }

    #[Test]
    public function it_shows_the_create_office_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/offices/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.offices._create_office');
    }

    #[Test]
    public function it_creates_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $country = Country::factory()->create();

        $response = $this->actingAs($user)
            ->post('/organizations/' . $organization->slug . '/adminland/offices', [
                'name' => 'Main Office',
                'office_type_id' => $officeType->id,
                'address_line_1' => '1725 Slough Avenue',
                'address_line_2' => 'Floor 2',
                'city' => 'Scranton',
                'state_province' => 'PA',
                'postal_code' => '18505',
                'country_id' => $country->id,
                'timezone' => 'America/New_York',
            ]);

        $response->assertRedirect(route('organization.adminland.office.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');

        $this->assertDatabaseHas('offices', [
            'organization_id' => $organization->id,
            'office_type_id' => $officeType->id,
            'country_id' => $country->id,
            'name' => 'Main Office',
            'address_line_1' => '1725 Slough Avenue',
            'address_line_2' => 'Floor 2',
            'city' => 'Scranton',
            'state_province' => 'PA',
            'postal_code' => '18505',
            'timezone' => 'America/New_York',
        ]);
    }

    #[Test]
    public function it_shows_the_edit_office_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id);

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.offices._edit_office');
        $response->assertViewHas('office', $office);
    }

    #[Test]
    public function it_preselects_country_and_office_type_in_edit_view(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $country = Country::factory()->create();
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'office_type_id' => $officeType->id,
            'country_id' => $country->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id);

        $response->assertStatus(200);
        $response->assertSee('value="' . $officeType->id . '" selected', false);
        $response->assertSee('value="' . $country->id . '" selected', false);
    }

    #[Test]
    public function it_returns_404_when_editing_an_office_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_updates_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $country = Country::factory()->create();
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Office',
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id, [
                'name' => 'Updated Office',
                'office_type_id' => $officeType->id,
                'address_line_1' => '12 Main Street',
                'address_line_2' => null,
                'city' => 'Paris',
                'state_province' => null,
                'postal_code' => '75001',
                'country_id' => $country->id,
                'timezone' => 'Europe/Paris',
            ]);

        $response->assertRedirect(route('organization.adminland.office.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');

        $this->assertDatabaseHas('offices', [
            'id' => $office->id,
            'name' => 'Updated Office',
            'office_type_id' => $officeType->id,
            'address_line_1' => '12 Main Street',
            'city' => 'Paris',
            'postal_code' => '75001',
            'country_id' => $country->id,
            'timezone' => 'Europe/Paris',
        ]);
    }

    #[Test]
    public function it_returns_404_when_updating_an_office_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id, [
                'name' => 'Updated Office',
                'address_line_1' => '12 Main Street',
                'city' => 'Paris',
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_deletes_an_office(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id);

        $response->assertRedirect(route('organization.adminland.office.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');

        $this->assertDatabaseMissing('offices', [
            'id' => $office->id,
        ]);
    }

    #[Test]
    public function it_returns_404_when_deleting_an_office_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/offices/' . $office->id);

        $response->assertStatus(404);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

use App\Models\OfficeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OfficeTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_create_office_type_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/office-types/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.offices._create_office_type');
    }

    #[Test]
    public function it_creates_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->post('/organizations/' . $organization->slug . '/adminland/office-types', [
                'name' => 'Branch Office',
            ]);

        $response->assertRedirect(route('organization.adminland.office.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_shows_the_edit_office_type_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/office-types/' . $officeType->id);

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.offices._edit_office_type');
        $response->assertViewHas('officeType', $officeType);
    }

    #[Test]
    public function it_returns_404_when_editing_an_office_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $officeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/office-types/' . $officeType->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_updates_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/office-types/' . $officeType->id, [
                'name' => 'New Name',
            ]);

        $response->assertRedirect(route('organization.adminland.office.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_updating_an_office_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $officeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/office-types/' . $officeType->id, [
                'name' => 'New Name',
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_deletes_an_office_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/office-types/' . $officeType->id);

        $response->assertRedirect(route('organization.adminland.office.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_deleting_an_office_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $officeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/office-types/' . $officeType->id);

        $response->assertStatus(404);
    }
}

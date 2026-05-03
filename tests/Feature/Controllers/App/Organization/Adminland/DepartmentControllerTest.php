<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_departments_index_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/departments');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.departments.index');
    }

    #[Test]
    public function it_shows_the_create_department_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/departments/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.departments._create_department');
    }

    #[Test]
    public function it_creates_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->post('/organizations/' . $organization->slug . '/adminland/departments', [
                'name' => 'Engineering',
            ]);

        $response->assertRedirect(route('organization.adminland.department.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_shows_the_edit_department_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/departments/' . $department->id);

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.departments._edit_department');
        $response->assertViewHas('department', $department);
    }

    #[Test]
    public function it_returns_404_when_editing_a_department_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $department = Department::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/departments/' . $department->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_updates_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/departments/' . $department->id, [
                'name' => 'New Name',
            ]);

        $response->assertRedirect(route('organization.adminland.department.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_updating_a_department_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $department = Department::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/departments/' . $department->id, [
                'name' => 'New Name',
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_deletes_a_department(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $department = Department::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/departments/' . $department->id);

        $response->assertRedirect(route('organization.adminland.department.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_deleting_a_department_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $department = Department::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/departments/' . $department->id);

        $response->assertStatus(404);
    }
}

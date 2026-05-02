<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

use App\Models\MemberType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_create_member_type_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/member-types/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.members._create_member_type');
    }

    #[Test]
    public function it_creates_a_member_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)
            ->post('/organizations/' . $organization->slug . '/adminland/member-types', [
                'name' => 'Contractor',
            ]);

        $response->assertRedirect(route('organization.adminland.member.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_shows_the_edit_member_type_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id);

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.adminland.members._edit_member_type');
        $response->assertViewHas('memberType', $memberType);
    }

    #[Test]
    public function it_returns_404_when_editing_a_member_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $memberType = MemberType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_updates_a_member_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id, [
                'name' => 'New Name',
            ]);

        $response->assertRedirect(route('organization.adminland.member.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_updating_a_member_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $memberType = MemberType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id, [
                'name' => 'New Name',
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_deletes_a_member_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id);

        $response->assertRedirect(route('organization.adminland.member.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_deleting_a_member_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $memberType = MemberType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_sorts_a_member_type(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        MemberType::factory()->create([
            'organization_id' => $organization->id,
            'position' => 0,
        ]);
        $memberType = MemberType::factory()->create([
            'organization_id' => $organization->id,
            'position' => 1,
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id, [
                'position' => 0,
            ]);

        $response->assertRedirect(route('organization.adminland.member.index', $organization->slug));
        $response->assertSessionHas('status', 'Changes saved');
    }

    #[Test]
    public function it_returns_404_when_sorting_a_member_type_from_another_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $otherOrganization = $this->addOrganization($this->createUser());
        $memberType = MemberType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->actingAs($user)
            ->put('/organizations/' . $organization->slug . '/adminland/member-types/' . $memberType->id, [
                'position' => 0,
            ]);

        $response->assertStatus(404);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JoinOrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_join_organization_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/organizations/join');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.join.create');
    }

    #[Test]
    public function it_joins_an_organization(): void
    {
        $user = $this->createUser();
        $organization = Organization::factory()->create([
            'invitation_code' => 'ABC123',
        ]);

        $response = $this->actingAs($user)->post('/organizations/join', [
            'invitation_code' => 'ABC123',
        ]);

        $response->assertRedirect('/organizations/' . $organization->slug);
        $response->assertSessionHas('status');
    }

    #[Test]
    public function it_fails_if_invitation_code_is_missing(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/organizations/join', []);

        $response->assertSessionHasErrors('invitation_code');
    }

    #[Test]
    public function it_fails_if_invitation_code_is_invalid(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/organizations/join', [
            'invitation_code' => 'INVALID',
        ]);

        $response->assertSessionHasErrors('invitation_code');
    }

    #[Test]
    public function it_fails_if_user_is_already_a_member(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $organization->update(['invitation_code' => 'ABC123']);

        $response = $this->actingAs($user)->post('/organizations/join', [
            'invitation_code' => 'ABC123',
        ]);

        $response->assertSessionHasErrors('invitation_code');
    }
}

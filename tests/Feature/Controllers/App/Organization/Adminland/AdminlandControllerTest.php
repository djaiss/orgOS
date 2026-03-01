<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

use App\Enums\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminlandControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_adminland_page(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $response = $this->actingAs($user)->get('/organizations/' . $organization->slug . '/adminland');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_cant_show_adminland_for_non_admins(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Member,
        );

        $response = $this->actingAs($user)->get('/organizations/' . $organization->slug . '/adminland');

        $response->assertStatus(403);
    }
}

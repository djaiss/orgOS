<?php

declare(strict_types=1);

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
        $response->assertViewHas('organizations', function ($organizations) {
            return $organizations->count() === 1
            && $organizations->every(fn ($org) => isset(
                $org->name,
                $org->link,
                $org->avatar)
            );
        });
    }
}

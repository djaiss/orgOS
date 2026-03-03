<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization\Adminland;

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

        Office::factory()->create([
            'organization_id' => $organization->id,
            'office_type_id' => $officeType->id,
            'name' => 'Main Office',
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
                && $offices->first()->name === 'Main Office',
        );
    }
}

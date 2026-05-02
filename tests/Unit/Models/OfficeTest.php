<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\Office;
use App\Models\OfficeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OfficeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_an_organization(): void
    {
        $office = Office::factory()->create();

        $this->assertTrue($office->organization()->exists());
    }

    #[Test]
    public function it_belongs_to_a_country(): void
    {
        $country = Country::factory()->create();
        $office = Office::factory()->create([
            'country_id' => $country->id,
        ]);

        $this->assertTrue($office->country()->exists());
    }

    #[Test]
    public function it_belongs_to_an_office_type(): void
    {
        $officeType = OfficeType::factory()->create();
        $office = Office::factory()->create([
            'office_type_id' => $officeType->id,
        ]);

        $this->assertTrue($office->officeType()->exists());
    }

    #[Test]
    public function it_formats_the_full_address(): void
    {
        $country = Country::factory()->create([
            'name' => 'United States',
        ]);

        $office = Office::factory()->create([
            'country_id' => $country->id,
            'address_line_1' => '1725 Slough Avenue',
            'address_line_2' => 'Suite 100',
            'city' => 'Scranton',
            'state_province' => 'PA',
            'postal_code' => '18505',
        ]);

        $this->assertSame(
            '1725 Slough Avenue, Suite 100, Scranton, PA 18505, United States',
            $office->address->format(),
        );
    }
}

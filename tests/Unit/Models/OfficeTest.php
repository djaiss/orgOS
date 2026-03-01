<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\Office;
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
}

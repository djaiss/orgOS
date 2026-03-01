<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\Office;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_many_offices(): void
    {
        $country = Country::factory()->create();
        Office::factory()->create([
            'country_id' => $country->id,
        ]);

        $this->assertTrue($country->offices()->exists());
    }
}

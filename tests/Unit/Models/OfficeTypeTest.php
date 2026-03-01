<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Office;
use App\Models\OfficeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OfficeTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_an_organization(): void
    {
        $officeType = OfficeType::factory()->create();

        $this->assertTrue($officeType->organization()->exists());
    }

    #[Test]
    public function it_has_many_offices(): void
    {
        $officeType = OfficeType::factory()->create();
        Office::factory()->create([
            'office_type_id' => $officeType->id,
            'organization_id' => $officeType->organization_id,
        ]);

        $this->assertTrue($officeType->offices()->exists());
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\MemberType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberTypeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_an_organization(): void
    {
        $memberType = MemberType::factory()->create();

        $this->assertTrue($memberType->organization()->exists());
    }
}

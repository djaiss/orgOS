<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_gets_avatar(): void
    {
        $organization = Organization::factory()->create();

        $avatar = $organization->getAvatar();

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $avatar);
    }
}

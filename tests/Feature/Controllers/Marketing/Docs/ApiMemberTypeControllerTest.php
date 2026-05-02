<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Marketing\Docs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiMemberTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_api_member_type_page(): void
    {
        $response = $this->get('/docs/api/organizations/membertypes');
        $response->assertOk();
    }
}

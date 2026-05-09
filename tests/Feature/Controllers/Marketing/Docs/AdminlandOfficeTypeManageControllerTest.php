<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Marketing\Docs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminlandOfficeTypeManageControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_manage_office_types_page(): void
    {
        $response = $this->get('/docs/offices/manage');
        $response->assertOk();
    }
}

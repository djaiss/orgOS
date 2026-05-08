<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Marketing\Docs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminlandDepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_adminland_department_page(): void
    {
        $response = $this->get('/docs/departments');
        $response->assertOk();
    }
}

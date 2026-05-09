<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Marketing\Docs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DocsIndexControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_docs_index_page(): void
    {
        $response = $this->get('/docs');
        $response->assertOk();
    }
}

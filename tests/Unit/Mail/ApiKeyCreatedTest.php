<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\ApiKeyCreated;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiKeyCreatedTest extends TestCase
{
    #[Test]
    public function it_should_have_correct_envelope_subject(): void
    {
        $mailable = new ApiKeyCreated(
            label: 'Production API Key',
        );

        $this->assertEquals('New API key added', $mailable->envelope()->subject);

        $rendered = $mailable->render();

        $this->assertStringContainsString('Production API Key', $rendered);
    }
}

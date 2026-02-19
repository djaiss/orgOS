<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\MagicLinkCreated;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MagicLinkCreatedTest extends TestCase
{
    #[Test]
    public function it_should_have_correct_envelope_subject(): void
    {
        Config::set('app.name', 'orgOS');

        $mailable = new MagicLinkCreated(
            link: 'https://example.com/magic-link/abc123',
        );

        $this->assertEquals('Login to orgOS', $mailable->envelope()->subject);

        $rendered = $mailable->render();

        $this->assertStringContainsString('https://example.com/magic-link/abc123', $rendered);
    }
}

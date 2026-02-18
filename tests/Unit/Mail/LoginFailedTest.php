<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\LoginFailed;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginFailedTest extends TestCase
{
    #[Test]
    public function it_should_have_correct_envelope_subject(): void
    {
        Config::set('app.name', 'orgOS');

        $mailable = new LoginFailed;

        $this->assertEquals('Login attempt on orgOS', $mailable->envelope()->subject);

        $rendered = $mailable->render();

        $this->assertStringContainsString('Login attempt on orgOS', $rendered);
    }
}

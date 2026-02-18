<?php

declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\AccountAutomaticallyDestroyed;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AccountAutomaticallyDestroyedTest extends TestCase
{
    #[Test]
    public function it_should_have_correct_envelope_subject(): void
    {
        $mailable = new AccountAutomaticallyDestroyed(
            age: '90 days',
        );

        $this->assertEquals('Account automatically deleted', $mailable->envelope()->subject);

        $rendered = $mailable->render();

        $this->assertStringContainsString('Account deleted', $rendered);
        $this->assertStringContainsString('automatically deleted because of inactivity', $rendered);
        $this->assertStringContainsString('90 days', $rendered);
    }
}

<?php

declare(strict_types = 1);

namespace Tests\Unit\Mail;

use App\Mail\UserIpAddressChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserIpAddressChangedTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_have_correct_envelope_subject(): void
    {
        Config::set('app.name', 'orgOS');

        $user = User::factory()->create([
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $mailable = new UserIpAddressChanged(
            user: $user,
            ip: '192.168.1.1',
        );

        $this->assertEquals('New sign-in detected on your orgOS account', $mailable->envelope()->subject);

        $rendered = $mailable->render();

        $this->assertStringContainsString('michael.scott@dundermifflin.com', $rendered);
        $this->assertStringContainsString('192.168.1.1', $rendered);
    }
}

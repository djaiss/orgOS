<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers\App\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PasswordResetLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_forgot_password_screen(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertViewIs('app.auth.forgot-password');
    }

    #[Test]
    public function it_sends_a_password_reset_link(): void
    {
        Notification::fake();

        User::factory()->create([
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }
}

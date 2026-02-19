<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_loads_the_verify_email_view(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
        $response->assertViewIs('app.auth.verify-email');
    }

    #[Test]
    public function it_redirects_to_the_dashboard_if_the_email_is_verified(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertRedirect(route('organization.index', absolute: false));
    }

    #[Test]
    public function it_resends_a_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/verify-email');

        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');
    }
}

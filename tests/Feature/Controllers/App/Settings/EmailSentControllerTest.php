<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Settings;

use App\Models\EmailSent;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailSentControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_all_the_emails(): void
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 1));
        $user = $this->createUser();

        $email = EmailSent::factory()->create([
            'user_id' => $user->id,
            'email_address' => 'test@example.com',
            'subject' => 'Test Email',
            'sent_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get('/settings/emails');

        $response->assertStatus(200);
        $response->assertViewIs('app.settings.emails.index');
        $response->assertViewHas('emails');
    }
}

<?php

declare(strict_types = 1);

namespace Tests\Unit\Models;

use App\Models\EmailSent;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailSentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_user(): void
    {
        $user = $this->createUser();
        $emailSent = EmailSent::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($emailSent->user()->exists());
    }
}

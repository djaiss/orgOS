<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateAccount;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_account(): void
    {
        Date::setTestNow(Date::create(2018, 1, 1));

        $user = new CreateAccount(
            email: 'michael.scott@dundermifflin.com',
            password: 'password',
            firstName: 'Michael',
            lastName: 'Scott',
        )->execute();

        $this->assertInstanceOf(
            User::class,
            $user,
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'michael.scott@dundermifflin.com',
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'trial_ends_at' => '2018-01-31 00:00:00',
        ]);
    }

    #[Test]
    public function it_cant_create_an_account_with_the_same_email(): void
    {
        User::factory()->create([
            'email' => 'michael.scott@dundermifflin.com',
        ]);

        $this->expectException(UniqueConstraintViolationException::class);

        new CreateAccount(
            email: 'michael.scott@dundermifflin.com',
            password: 'password',
            firstName: 'Michael',
            lastName: 'Scott',
        )->execute();
    }
}

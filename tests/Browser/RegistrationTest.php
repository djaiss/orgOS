<?php

declare(strict_types = 1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class RegistrationTest extends DuskTestCase
{
    #[Test]
    public function it_registers_a_new_user(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser
                ->visit('/register')
                ->type('first_name', 'Dwight')
                ->type('last_name', 'Schrute')
                ->type('email', 'dwight.schrute@example.com')
                ->type('password', 'password1234')
                ->type('password_confirmation', 'password1234')
                ->press('Next step: validate your email address')
                ->assertPathIs('/organization');
        });
    }
}

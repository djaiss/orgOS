<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocaleControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_update_locale(): void
    {
        $response = $this->from('/')
            ->put('/locale', [
                'locale' => 'fr',
            ]);

        $response->assertRedirect('/');
        $this->assertEquals('fr', session('locale'));
        $this->assertEquals('fr', App::getLocale());
    }

    #[Test]
    public function it_updates_authenticated_user_locale(): void
    {
        $user = $this->createUser([
            'locale' => 'en',
        ]);

        $response = $this->actingAs($user)
            ->from('/')
            ->put('/locale', [
                'locale' => 'fr',
            ]);

        $response->assertRedirect('/');
        $this->assertEquals('fr', session('locale'));
        $this->assertEquals('fr', App::getLocale());
        $this->assertEquals('fr', $user->fresh()->locale);
    }
}

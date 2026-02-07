<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_loads_the_verify_email_view(): void
    {

    }

    #[Test]
    public function it_redirects_to_the_dashboard_if_the_email_is_verified(): void
    {

    }

    #[Test]
    public function it_resends_a_verification_email(): void
    {

    }
}

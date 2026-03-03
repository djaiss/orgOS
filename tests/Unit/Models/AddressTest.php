<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_formats_a_full_address(): void
    {
        $address = new Address(
            line1: '1725 Slough Avenue',
            line2: 'Suite 100',
            city: 'Scranton',
            stateProvince: 'PA',
            postalCode: '18505',
            country: 'United States',
        );

        $this->assertSame('1725 Slough Avenue, Suite 100, Scranton, PA 18505, United States', $address->format());
    }

    #[Test]
    public function it_formats_without_address_line_2(): void
    {
        $address = new Address(
            line1: '1725 Slough Avenue',
            line2: null,
            city: 'Scranton',
            stateProvince: 'PA',
            postalCode: '18505',
            country: 'United States',
        );

        $this->assertSame('1725 Slough Avenue, Scranton, PA 18505, United States', $address->format());
    }

    #[Test]
    public function it_formats_without_state_province(): void
    {
        $address = new Address(
            line1: '1725 Slough Avenue',
            line2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: '18505',
            country: 'United States',
        );

        $this->assertSame('1725 Slough Avenue, Scranton, 18505, United States', $address->format());
    }

    #[Test]
    public function it_formats_without_postal_code(): void
    {
        $address = new Address(
            line1: '1725 Slough Avenue',
            line2: null,
            city: 'Scranton',
            stateProvince: 'PA',
            postalCode: null,
            country: 'United States',
        );

        $this->assertSame('1725 Slough Avenue, Scranton, PA, United States', $address->format());
    }

    #[Test]
    public function it_formats_without_state_and_postal_code(): void
    {
        $address = new Address(
            line1: '1725 Slough Avenue',
            line2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: null,
            country: 'United States',
        );

        $this->assertSame('1725 Slough Avenue, Scranton, United States', $address->format());
    }

    #[Test]
    public function it_formats_without_country(): void
    {
        $address = new Address(
            line1: '1725 Slough Avenue',
            line2: null,
            city: 'Scranton',
            stateProvince: 'PA',
            postalCode: '18505',
            country: null,
        );

        $this->assertSame('1725 Slough Avenue, Scranton, PA 18505', $address->format());
    }

    #[Test]
    public function it_returns_an_empty_string_when_all_fields_are_null(): void
    {
        $address = new Address(
            line1: null,
            line2: null,
            city: null,
            stateProvince: null,
            postalCode: null,
            country: null,
        );

        $this->assertSame('', $address->format());
    }
}

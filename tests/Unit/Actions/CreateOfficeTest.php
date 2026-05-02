<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateOffice;
use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\Country;
use App\Models\Office;
use App\Models\OfficeType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateOfficeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_office(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $country = Country::factory()->create();
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $office = new CreateOffice(
            user: $user,
            organization: $organization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: 'Floor 2',
            city: 'Scranton',
            stateProvince: 'PA',
            postalCode: '18505',
            timezone: 'America/New_York',
            countryId: $country->id,
            officeTypeId: $officeType->id,
        )->execute();

        $this->assertInstanceOf(Office::class, $office);

        $this->assertDatabaseHas('offices', [
            'id' => $office->id,
            'organization_id' => $organization->id,
            'country_id' => $country->id,
            'office_type_id' => $officeType->id,
            'name' => 'Main Office',
            'address_line_1' => '1725 Slough Avenue',
            'address_line_2' => 'Floor 2',
            'city' => 'Scranton',
            'state_province' => 'PA',
            'postal_code' => '18505',
            'timezone' => 'America/New_York',
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'office_creation'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Created an office called Main Office'
            ),
        );
    }

    #[Test]
    public function it_converts_empty_optional_fields_to_null(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        $office = new CreateOffice(
            user: $user,
            organization: $organization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: '<b></b>',
            city: 'Scranton',
            stateProvince: '   ',
            postalCode: '<i></i>',
            timezone: '',
        )->execute();

        $this->assertNull($office->address_line_2);
        $this->assertNull($office->state_province);
        $this->assertNull($office->postal_code);
        $this->assertNull($office->timezone);
        $this->assertNull($office->country_id);
        $this->assertNull($office->office_type_id);
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_not_part_of_the_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $otherOrganization = $this->addOrganization($this->createUser());

        new CreateOffice(
            user: $user,
            organization: $otherOrganization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: null,
            timezone: null,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_a_member(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Member,
        );

        new CreateOffice(
            user: $user,
            organization: $organization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: null,
            timezone: null,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_a_guest(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization(
            user: $user,
            permission: Permission::Guest,
        );

        new CreateOffice(
            user: $user,
            organization: $organization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: null,
            timezone: null,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_office_type_does_not_belong_to_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $otherOrganization = $this->addOrganization($this->createUser());
        $officeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new CreateOffice(
            user: $user,
            organization: $organization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: null,
            timezone: null,
            officeTypeId: $officeType->id,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_country_does_not_exist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        new CreateOffice(
            user: $user,
            organization: $organization,
            name: 'Main Office',
            addressLine1: '1725 Slough Avenue',
            addressLine2: null,
            city: 'Scranton',
            stateProvince: null,
            postalCode: null,
            timezone: null,
            countryId: 999_999,
        )->execute();
    }

    #[Test]
    public function it_throws_a_validation_exception_if_required_fields_are_not_plain_text(): void
    {
        $this->expectException(ValidationException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);

        new CreateOffice(
            user: $user,
            organization: $organization,
            name: '<b></b>',
            addressLine1: '<script>alert(1)</script>',
            addressLine2: null,
            city: '<div></div>',
            stateProvince: null,
            postalCode: null,
            timezone: null,
        )->execute();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\UpdateOffice;
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

class UpdateOfficeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_an_office(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $country = Country::factory()->create();
        $officeType = OfficeType::factory()->create([
            'organization_id' => $organization->id,
        ]);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
            'name' => 'Old Office',
            'address_line_1' => 'Old Address',
            'city' => 'Old City',
        ]);

        $updatedOffice = new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: 'Suite 100',
            city: 'New City',
            stateProvince: 'PA',
            postalCode: '18505',
            timezone: 'America/New_York',
            countryId: $country->id,
            officeTypeId: $officeType->id,
        )->execute();

        $this->assertSame('New Office', $updatedOffice->name);

        $this->assertDatabaseHas('offices', [
            'id' => $office->id,
            'country_id' => $country->id,
            'office_type_id' => $officeType->id,
            'name' => 'New Office',
            'address_line_1' => 'New Address',
            'address_line_2' => 'Suite 100',
            'city' => 'New City',
            'state_province' => 'PA',
            'postal_code' => '18505',
            'timezone' => 'America/New_York',
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'office_update'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Updated an office called New Office'
            ),
        );
    }

    #[Test]
    public function it_sets_optional_fields_to_null_when_empty(): void
    {
        Queue::fake();

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => Country::factory()->create()->id,
            'office_type_id' => OfficeType::factory()->create([
                'organization_id' => $organization->id,
            ])->id,
            'address_line_2' => 'Floor 2',
            'state_province' => 'PA',
            'postal_code' => '18505',
            'timezone' => 'America/New_York',
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: ' ',
            city: 'New City',
            stateProvince: '<b></b>',
            postalCode: '',
            timezone: '<i></i>',
        )->execute();

        $this->assertDatabaseHas('offices', [
            'id' => $office->id,
            'address_line_2' => null,
            'state_province' => null,
            'postal_code' => null,
            'timezone' => null,
            'country_id' => null,
            'office_type_id' => null,
        ]);
    }

    #[Test]
    public function it_throws_an_exception_if_user_is_not_part_of_the_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: null,
            city: 'New City',
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
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: null,
            city: 'New City',
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
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: null,
            city: 'New City',
            stateProvince: null,
            postalCode: null,
            timezone: null,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_office_does_not_belong_to_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $otherOrganization = $this->addOrganization($this->createUser());
        $office = Office::factory()->create([
            'organization_id' => $otherOrganization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: null,
            city: 'New City',
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
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);
        $otherOfficeType = OfficeType::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: null,
            city: 'New City',
            stateProvince: null,
            postalCode: null,
            timezone: null,
            officeTypeId: $otherOfficeType->id,
        )->execute();
    }

    #[Test]
    public function it_throws_an_exception_if_country_does_not_exist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $user = $this->createUser();
        $organization = $this->addOrganization($user);
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
            name: 'New Office',
            addressLine1: 'New Address',
            addressLine2: null,
            city: 'New City',
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
        $office = Office::factory()->create([
            'organization_id' => $organization->id,
            'country_id' => null,
            'office_type_id' => null,
        ]);

        new UpdateOffice(
            user: $user,
            organization: $organization,
            office: $office,
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

<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Member;
use App\Helpers\TextSanitizer;
use App\Jobs\LogUserAction;
use App\Models\Country;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CreateOffice
{
    private Office $office;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private string $name,
        private string $addressLine1,
        private ?string $addressLine2,
        private string $city,
        private ?string $stateProvince,
        private ?string $postalCode,
        private ?string $timezone,
        private readonly ?int $countryId = null,
        private readonly ?int $officeTypeId = null,
    ) {}

    public function execute(): Office
    {
        $this->validate();
        $this->create();
        $this->log();

        return $this->office;
    }

    private function validate(): void
    {
        $this->name = TextSanitizer::plainText($this->name);
        $this->addressLine1 = TextSanitizer::plainText($this->addressLine1);
        $this->addressLine2 = TextSanitizer::nullablePlainText($this->addressLine2);
        $this->city = TextSanitizer::plainText($this->city);
        $this->stateProvince = TextSanitizer::nullablePlainText($this->stateProvince);
        $this->postalCode = TextSanitizer::nullablePlainText($this->postalCode);
        $this->timezone = TextSanitizer::nullablePlainText($this->timezone);

        $member = $this->user->memberOf($this->organization);

        if (!$member instanceof Member) {
            throw new ModelNotFoundException('Organization not found');
        }

        if ($member->isOwner() === false && $member->isAdministrator() === false) {
            throw new ModelNotFoundException('Organization not found');
        }

        $messages = [];

        if ($this->name === '') {
            $messages['name'] = 'Name must be plain text.';
        }

        if ($this->addressLine1 === '') {
            $messages['address_line_1'] = 'Address line 1 must be plain text.';
        }

        if ($this->city === '') {
            $messages['city'] = 'City must be plain text.';
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }

        if ($this->countryId !== null && Country::query()->whereKey($this->countryId)->exists() === false) {
            throw new ModelNotFoundException('Country not found');
        }

        if ($this->officeTypeId !== null && OfficeType::query()
            ->whereKey($this->officeTypeId)
            ->where('organization_id', $this->organization->id)->exists() === false) {
            throw new ModelNotFoundException('Office type not found');
        }
    }

    private function create(): void
    {
        $this->office = Office::query()->create([
            'organization_id' => $this->organization->id,
            'country_id' => $this->countryId,
            'office_type_id' => $this->officeTypeId,
            'name' => $this->name,
            'address_line_1' => $this->addressLine1,
            'address_line_2' => $this->addressLine2,
            'city' => $this->city,
            'state_province' => $this->stateProvince,
            'postal_code' => $this->postalCode,
            'timezone' => $this->timezone,
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'office_creation',
            description: sprintf('Created an office called %s', $this->name),
        )->onQueue('low');
    }
}

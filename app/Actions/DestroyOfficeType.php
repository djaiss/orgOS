<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Member;
use App\Jobs\LogUserAction;
use App\Models\OfficeType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Destroy an office type for an organization.
 */
class DestroyOfficeType
{
    private readonly string $officeTypeName;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private readonly OfficeType $officeType,
    ) {
        $this->officeTypeName = $this->officeType->name;
    }

    public function execute(): void
    {
        $this->validate();
        $this->delete();
        $this->log();
    }

    private function validate(): void
    {
        $member = $this->user->memberOf($this->organization);

        throw_if(!$member instanceof Member, ModelNotFoundException::class, 'Organization not found');
        throw_if($member->isOwner() === false && $member->isAdministrator() === false, ModelNotFoundException::class, 'Organization not found');
        throw_if($this->officeType->organization_id !== $this->organization->id, ModelNotFoundException::class, 'Office type not found');
    }

    private function delete(): void
    {
        $this->officeType->delete();
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'office_type_deletion',
            description: sprintf('Deleted an office type called %s', $this->officeTypeName),
        )->onQueue('low');
    }
}

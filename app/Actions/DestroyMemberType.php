<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Destroy a member type for an organization.
 */
class DestroyMemberType
{
    private readonly string $memberTypeName;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private readonly MemberType $memberType,
    ) {
        $this->memberTypeName = $this->memberType->name;
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

        if (!$member instanceof Member) {
            throw new ModelNotFoundException('Organization not found');
        }

        if ($member->isOwner() === false && $member->isAdministrator() === false) {
            throw new ModelNotFoundException('Organization not found');
        }

        if ($this->memberType->organization_id !== $this->organization->id) {
            throw new ModelNotFoundException('Member type not found');
        }
    }

    private function delete(): void
    {
        $this->memberType->delete();
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'member_type_deletion',
            description: sprintf('Deleted a member type called %s', $this->memberTypeName),
        )->onQueue('low');
    }
}

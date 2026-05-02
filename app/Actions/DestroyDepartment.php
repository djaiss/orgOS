<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\Department;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Destroy a department for an organization.
 */
class DestroyDepartment
{
    private readonly string $departmentName;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private readonly Department $department,
    ) {
        $this->departmentName = $this->department->name;
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

        if ($this->department->organization_id !== $this->organization->id) {
            throw new ModelNotFoundException('Department not found');
        }
    }

    private function delete(): void
    {
        $this->department->delete();
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'department_deletion',
            description: sprintf('Deleted a department called %s', $this->departmentName),
        )->onQueue('low');
    }
}

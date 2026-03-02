<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\Office;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyOffice
{
    private readonly string $officeName;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private readonly Office $office,
    ) {
        $this->officeName = $this->office->name;
    }

    public function execute(): void
    {
        $this->validate();
        $this->delete();
        $this->log();
    }

    private function validate(): void
    {
        throw_if($this->user->isPartOfOrganization($this->organization) === false, ModelNotFoundException::class, 'Organization not found');
        throw_if($this->office->organization_id !== $this->organization->id, ModelNotFoundException::class, 'Office not found');
    }

    private function delete(): void
    {
        $this->office->delete();
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'office_deletion',
            description: sprintf('Deleted an office called %s', $this->officeName),
        )->onQueue('low');
    }
}

<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Member;
use App\Helpers\TextSanitizer;
use App\Jobs\LogUserAction;
use App\Models\OfficeType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Update an office type for an organization.
 */
class UpdateOfficeType
{
    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private readonly OfficeType $officeType,
        private string $name,
        private readonly ?int $position = null,
    ) {}

    public function execute(): OfficeType
    {
        $this->validate();
        $this->update();
        $this->log();

        return $this->officeType;
    }

    private function validate(): void
    {
        $this->name = TextSanitizer::plainText($this->name);

        $member = $this->user->memberOf($this->organization);

        if (!$member instanceof Member) {
            throw new ModelNotFoundException('Organization not found');
        }

        if ($member->isOwner() === false && $member->isAdministrator() === false) {
            throw new ModelNotFoundException('Organization not found');
        }

        if ($this->officeType->organization_id !== $this->organization->id) {
            throw new ModelNotFoundException('Office type not found');
        }
    }

    private function update(): void
    {
        $data = ['name' => $this->name];

        if ($this->position !== null && $this->position !== $this->officeType->position) {
            $this->reorder();
            $data['position'] = $this->position;
        }

        $this->officeType->update($data);
    }

    private function reorder(): void
    {
        $oldPosition = $this->officeType->position;
        $newPosition = $this->position;

        if ($newPosition < $oldPosition) {
            // Moving up: push items between new and old down by 1
            OfficeType::query()
                ->where('organization_id', $this->organization->id)
                ->where('position', '>=', $newPosition)
                ->where('position', '<', $oldPosition)
                ->increment('position');
        } else {
            // Moving down: pull items between old and new up by 1
            OfficeType::query()
                ->where('organization_id', $this->organization->id)
                ->where('position', '>', $oldPosition)
                ->where('position', '<=', $newPosition)
                ->decrement('position');
        }
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'office_type_update',
            description: sprintf('Updated an office type called %s', $this->name),
        )->onQueue('low');
    }
}

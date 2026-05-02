<?php

declare(strict_types=1);

namespace App\Actions;

use App\Helpers\TextSanitizer;
use App\Jobs\LogUserAction;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Update a member type for an organization.
 */
class UpdateMemberType
{
    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private readonly MemberType $memberType,
        private string $name,
        private readonly ?int $position = null,
    ) {}

    public function execute(): MemberType
    {
        $this->validate();
        $this->update();
        $this->log();

        return $this->memberType;
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

        if ($this->memberType->organization_id !== $this->organization->id) {
            throw new ModelNotFoundException('Member type not found');
        }
    }

    private function update(): void
    {
        $data = ['name' => $this->name];

        if ($this->position !== null && $this->position !== $this->memberType->position) {
            $this->reorder();
            $data['position'] = $this->position;
        }

        $this->memberType->update($data);
    }

    private function reorder(): void
    {
        $oldPosition = $this->memberType->position;
        $newPosition = $this->position;

        if ($newPosition < $oldPosition) {
            // Moving up: push items between new and old down by 1
            MemberType::query()
                ->where('organization_id', $this->organization->id)
                ->where('position', '>=', $newPosition)
                ->where('position', '<', $oldPosition)
                ->increment('position');
        } else {
            // Moving down: pull items between old and new up by 1
            MemberType::query()
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
            action: 'member_type_update',
            description: sprintf('Updated a member type called %s', $this->name),
        )->onQueue('low');
    }
}

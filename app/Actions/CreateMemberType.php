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
 * Create a member type for an organization.
 */
class CreateMemberType
{
    private MemberType $memberType;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private string $name,
        private ?int $position = null,
    ) {}

    public function execute(): MemberType
    {
        $this->validate();
        $this->shiftDown();
        $this->create();
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

        if ($this->position === null) {
            $this->position = MemberType::query()
                ->where('organization_id', $this->organization->id)
                ->count();
        }
    }

    private function shiftDown(): void
    {
        MemberType::query()
            ->where('organization_id', $this->organization->id)
            ->where('position', '>=', $this->position)
            ->increment('position');
    }

    private function create(): void
    {
        $this->memberType = MemberType::query()->create([
            'organization_id' => $this->organization->id,
            'name' => $this->name,
            'position' => $this->position,
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'member_type_creation',
            description: sprintf('Created a member type called %s', $this->name),
        )->onQueue('low');
    }
}

<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class JoinOrganization
{
    private Organization $organization;

    public function __construct(
        private readonly User $user,
        private readonly string $invitationCode,
    ) {}

    public function execute(): Organization
    {
        $this->validate();
        $this->join();
        $this->log();

        return $this->organization;
    }

    private function validate(): void
    {
        try {
            $this->organization = Organization::query()
                ->where('invitation_code', $this->invitationCode)
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw ValidationException::withMessages([
                'invitation_code' => 'Invalid invitation code',
            ]);
        }
        if ($this->user->isPartOfOrganization($this->organization)) {
            throw ValidationException::withMessages([
                'invitation_code' => 'You are already a member of this organization',
            ]);
        }
    }

    private function join(): void
    {
        Member::query()->create([
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'joined_at' => now(),
            'permission' => Permission::Member,
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'organization_joined',
            description: 'Joined organization',
        )->onQueue('low');
    }
}

<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Permission;
use App\Jobs\LogUserAction;
use App\Jobs\PopulateOrganization;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Create an organization for a user.
 * The user will be added to the organization as the first user.
 */
class CreateOrganization
{
    private Organization $organization;

    public function __construct(
        public User $user,
        public string $name,
    ) {}

    public function execute(): Organization
    {
        $this->validate();
        $this->create();
        $this->generateSlug();
        $this->generateInvitationCode();
        $this->addMembership();
        $this->populate();
        $this->log();

        return $this->organization;
    }

    private function validate(): void
    {
        // make sure the organization name doesn't contain any special characters
        if (in_array(preg_match('/^[a-zA-Z0-9\s\-_]+$/', $this->name), [0, false], true)) {
            throw ValidationException::withMessages([
                'organization_name' => 'Organization name can only contain letters, numbers, spaces, hyphens and underscores',
            ]);
        }
    }

    private function create(): void
    {
        $this->organization = Organization::query()->create([
            'name' => $this->name,
        ]);
    }

    private function generateSlug(): void
    {
        $slug = $this->organization->id . '-' . Str::of($this->name)->slug('-');

        $this->organization->slug = $slug;
    }

    private function generateInvitationCode(): void
    {
        $this->organization->invitation_code = Str::random(64);
        $this->organization->save();
    }

    private function addMembership(): void
    {
        Member::query()->create([
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'joined_at' => now(),
            'permission' => Permission::Owner,
        ]);
    }

    private function populate(): void
    {
        PopulateOrganization::dispatch($this->organization)->onQueue('low');
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'organization_creation',
            description: sprintf('Created an organization called %s', $this->name),
        )->onQueue('low');
    }
}

<?php

declare(strict_types=1);

namespace App\Actions;

use App\Helpers\TextSanitizer;
use App\Jobs\LogUserAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpdateOrganization
{
    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private string $name,
    ) {}

    public function execute(): Organization
    {
        $this->validate();
        $this->rename();
        $this->log();

        return $this->organization;
    }

    private function validate(): void
    {
        $this->name = TextSanitizer::plainText($this->name);

        if ($this->user->isPartOfOrganization($this->organization) === false) {
            throw new ModelNotFoundException('Organization not found');
        }

        if (in_array(preg_match('/^[a-zA-Z0-9\s\-_]+$/', $this->name), [0, false], true)) {
            throw ValidationException::withMessages([
                'organization_name' => 'Organization name can only contain letters, numbers, spaces, hyphens and underscores',
            ]);
        }
    }

    private function rename(): void
    {
        $this->organization->update([
            'name' => $this->name,
            'slug' => $this->organization->id . '-' . Str::of($this->name)->slug('-'),
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'organization_update',
            description: sprintf('Updated the organization called %s', $this->name),
        )->onQueue('low');
    }
}

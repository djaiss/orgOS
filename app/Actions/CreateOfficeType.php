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
 * Create an office type for an organization.
 */
class CreateOfficeType
{
    private OfficeType $officeType;

    public function __construct(
        private readonly User $user,
        private readonly Organization $organization,
        private string $name,
        private ?int $position = null,
    ) {}

    public function execute(): OfficeType
    {
        $this->validate();
        $this->shiftDown();
        $this->create();
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

        if ($this->position === null) {
            $this->position = OfficeType::query()
                ->where('organization_id', $this->organization->id)
                ->count();
        }
    }

    private function shiftDown(): void
    {
        OfficeType::query()
            ->where('organization_id', $this->organization->id)
            ->where('position', '>=', $this->position)
            ->increment('position');
    }

    private function create(): void
    {
        $this->officeType = OfficeType::query()->create([
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
            action: 'office_type_creation',
            description: sprintf('Created an office type called %s', $this->name),
        )->onQueue('low');
    }
}

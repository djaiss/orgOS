<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Organization;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PopulateOrganization implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Organization $organization,
    ) {}

    /**
     * Populate the organization with initial data.
     */
    public function handle(): void
    {
        $this->addOfficeTypes();
    }

    private function addOfficeTypes(): void
    {
        $types = [
            'Headquarters',
            'Office',
            'Remote',
            'Coworking',
            'Other',
        ];

        foreach ($types as $position => $name) {
            $this->organization->officeTypes()->create([
                'name' => $name,
                'position' => $position,
            ]);
        }
    }
}

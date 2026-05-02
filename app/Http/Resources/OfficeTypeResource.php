<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\OfficeType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OfficeType
 */
class OfficeTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'office_type',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'position' => $this->position,
                'created_at' => $this->created_at->timestamp,
                'updated_at' => $this->updated_at->timestamp,
            ],
            'links' => [
                'self' => route('api.organization.adminland.officetype.show', [
                    'id' => $this->organization_id,
                    'officeTypeId' => $this->id,
                ]),
            ],
        ];
    }
}

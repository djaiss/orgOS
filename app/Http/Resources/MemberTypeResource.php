<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MemberType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MemberType
 */
class MemberTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'member_type',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'position' => $this->position,
                'created_at' => $this->created_at->timestamp,
                'updated_at' => $this->updated_at->timestamp,
            ],
            'links' => [
                'self' => route('api.organization.adminland.membertype.show', [
                    'id' => $this->organization_id,
                    'memberTypeId' => $this->id,
                ]),
            ],
        ];
    }
}

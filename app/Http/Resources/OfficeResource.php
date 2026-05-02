<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Office
 */
class OfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'office',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state_province' => $this->state_province,
                'postal_code' => $this->postal_code,
                'timezone' => $this->timezone,
                'country_id' => $this->country_id,
                'office_type_id' => $this->office_type_id,
                'created_at' => $this->created_at->timestamp,
                'updated_at' => $this->updated_at->timestamp,
            ],
            'links' => [
                'self' => route('api.organization.adminland.office.show', [
                    'id' => $this->organization_id,
                    'officeId' => $this->id,
                ]),
            ],
        ];
    }
}

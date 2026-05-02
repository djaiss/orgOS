<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Adminland;

use App\Actions\CreateOffice;
use App\Actions\DestroyOffice;
use App\Actions\UpdateOffice;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OfficeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');

        $offices = $organization->offices()
            ->orderBy('name')
            ->get();

        return OfficeResource::collection($offices);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state_province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'integer'],
            'office_type_id' => ['nullable', 'integer'],
        ]);

        $office = new CreateOffice(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
            addressLine1: TextSanitizer::plainText($validated['address_line_1']),
            addressLine2: TextSanitizer::nullablePlainText($validated['address_line_2'] ?? null),
            city: TextSanitizer::plainText($validated['city']),
            stateProvince: TextSanitizer::nullablePlainText($validated['state_province'] ?? null),
            postalCode: TextSanitizer::nullablePlainText($validated['postal_code'] ?? null),
            timezone: TextSanitizer::nullablePlainText($validated['timezone'] ?? null),
            countryId: isset($validated['country_id']) ? (int) $validated['country_id'] : null,
            officeTypeId: isset($validated['office_type_id']) ? (int) $validated['office_type_id'] : null,
        )->execute();

        return new OfficeResource($office)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $id, int $officeId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $office = $organization->offices()->findOrFail($officeId);

        return new OfficeResource($office)
            ->response()
            ->setStatusCode(200);
    }

    public function update(Request $request, int $id, int $officeId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $office = $organization->offices()->findOrFail($officeId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state_province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'integer'],
            'office_type_id' => ['nullable', 'integer'],
        ]);

        $office = new UpdateOffice(
            user: $request->user(),
            organization: $organization,
            office: $office,
            name: TextSanitizer::plainText($validated['name']),
            addressLine1: TextSanitizer::plainText($validated['address_line_1']),
            addressLine2: TextSanitizer::nullablePlainText($validated['address_line_2'] ?? null),
            city: TextSanitizer::plainText($validated['city']),
            stateProvince: TextSanitizer::nullablePlainText($validated['state_province'] ?? null),
            postalCode: TextSanitizer::nullablePlainText($validated['postal_code'] ?? null),
            timezone: TextSanitizer::nullablePlainText($validated['timezone'] ?? null),
            countryId: isset($validated['country_id']) ? (int) $validated['country_id'] : null,
            officeTypeId: isset($validated['office_type_id']) ? (int) $validated['office_type_id'] : null,
        )->execute();

        return new OfficeResource($office)
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request, int $id, int $officeId): Response
    {
        $organization = $request->attributes->get('organization');

        $office = $organization->offices()->findOrFail($officeId);

        new DestroyOffice(
            user: $request->user(),
            organization: $organization,
            office: $office,
        )->execute();

        return response()->noContent(204);
    }
}

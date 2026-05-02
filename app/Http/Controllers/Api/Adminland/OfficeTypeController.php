<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Adminland;

use App\Actions\CreateOfficeType;
use App\Actions\DestroyOfficeType;
use App\Actions\UpdateOfficeType;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OfficeTypeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');

        $officeTypes = $organization->officeTypes()
            ->orderBy('position')
            ->get();

        return OfficeTypeResource::collection($officeTypes);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $officeType = new CreateOfficeType(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
            position: $validated['position'] ?? null,
        )->execute();

        return new OfficeTypeResource($officeType)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $id, int $officeTypeId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $officeType = $organization->officeTypes()->findOrFail($officeTypeId);

        return new OfficeTypeResource($officeType)
            ->response()
            ->setStatusCode(200);
    }

    public function update(Request $request, int $id, int $officeTypeId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $officeType = $organization->officeTypes()->findOrFail($officeTypeId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $officeType = new UpdateOfficeType(
            user: $request->user(),
            organization: $organization,
            officeType: $officeType,
            name: TextSanitizer::plainText($validated['name']),
            position: $validated['position'] ?? null,
        )->execute();

        return new OfficeTypeResource($officeType)
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request, int $id, int $officeTypeId): Response
    {
        $organization = $request->attributes->get('organization');

        $officeType = $organization->officeTypes()->findOrFail($officeTypeId);

        new DestroyOfficeType(
            user: $request->user(),
            organization: $organization,
            officeType: $officeType,
        )->execute();

        return response()->noContent(204);
    }
}

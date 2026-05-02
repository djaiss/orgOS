<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Adminland;

use App\Actions\CreateMemberType;
use App\Actions\DestroyMemberType;
use App\Actions\UpdateMemberType;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MemberTypeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');

        $memberTypes = $organization->memberTypes()
            ->orderBy('position')
            ->get();

        return MemberTypeResource::collection($memberTypes);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $memberType = new CreateMemberType(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
            position: $validated['position'] ?? null,
        )->execute();

        return new MemberTypeResource($memberType)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $id, int $memberTypeId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $memberType = $organization->memberTypes()->findOrFail($memberTypeId);

        return new MemberTypeResource($memberType)
            ->response()
            ->setStatusCode(200);
    }

    public function update(Request $request, int $id, int $memberTypeId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $memberType = $organization->memberTypes()->findOrFail($memberTypeId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $memberType = new UpdateMemberType(
            user: $request->user(),
            organization: $organization,
            memberType: $memberType,
            name: TextSanitizer::plainText($validated['name']),
            position: $validated['position'] ?? null,
        )->execute();

        return new MemberTypeResource($memberType)
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request, int $id, int $memberTypeId): Response
    {
        $organization = $request->attributes->get('organization');

        $memberType = $organization->memberTypes()->findOrFail($memberTypeId);

        new DestroyMemberType(
            user: $request->user(),
            organization: $organization,
            memberType: $memberType,
        )->execute();

        return response()->noContent(204);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateOrganization;
use App\Actions\DestroyOrganization;
use App\Actions\UpdateOrganization;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrganizationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $organizations = $request->user()
            ->organizations()
            ->orderBy('id')
            ->get();

        return OrganizationResource::collection($organizations);
    }

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $organization = new CreateOrganization(
            user: $request->user(),
            name: TextSanitizer::plainText($validated['name']),
        )->execute();

        return new OrganizationResource($organization)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        return new OrganizationResource($organization)
            ->response()
            ->setStatusCode(200);
    }

    public function update(Request $request): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        new UpdateOrganization(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
        )->execute();

        return new OrganizationResource($organization)
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request): Response
    {
        $organization = $request->attributes->get('organization');

        new DestroyOrganization(
            user: $request->user(),
            organization: $organization,
        )->execute();

        return response()->noContent(204);
    }
}

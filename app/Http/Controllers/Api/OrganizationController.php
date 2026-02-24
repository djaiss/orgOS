<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateOrganization;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use Illuminate\Http\JsonResponse;
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

    // public function update(Request $request): JsonResponse
    // {
    //     $journal = $request->attributes->get('journal');

    //     $validated = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //     ]);

    //     new RenameJournal(
    //         user: Auth::user(),
    //         journal: $journal,
    //         name: TextSanitizer::plainText($validated['name']),
    //     )->execute();

    //     return new JournalResource($journal)
    //         ->response()
    //         ->setStatusCode(200);
    // }

    // public function destroy(Request $request): Response
    // {
    //     $journal = $request->attributes->get('journal');

    //     new DestroyJournal(
    //         user: Auth::user(),
    //         journal: $journal,
    //     )->execute();

    //     return response()->noContent(204);
    // }
}

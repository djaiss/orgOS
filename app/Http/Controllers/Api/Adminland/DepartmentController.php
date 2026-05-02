<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Adminland;

use App\Actions\CreateDepartment;
use App\Actions\DestroyDepartment;
use App\Actions\UpdateDepartment;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $organization = $request->attributes->get('organization');

        $departments = $organization->departments()
            ->orderBy('position')
            ->get();

        return DepartmentResource::collection($departments);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $department = new CreateDepartment(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
            position: $validated['position'] ?? null,
        )->execute();

        return new DepartmentResource($department)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $id, int $departmentId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $department = $organization->departments()->findOrFail($departmentId);

        return new DepartmentResource($department)
            ->response()
            ->setStatusCode(200);
    }

    public function update(Request $request, int $id, int $departmentId): JsonResponse
    {
        $organization = $request->attributes->get('organization');

        $department = $organization->departments()->findOrFail($departmentId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        $department = new UpdateDepartment(
            user: $request->user(),
            organization: $organization,
            department: $department,
            name: TextSanitizer::plainText($validated['name']),
            position: $validated['position'] ?? null,
        )->execute();

        return new DepartmentResource($department)
            ->response()
            ->setStatusCode(200);
    }

    public function destroy(Request $request, int $id, int $departmentId): Response
    {
        $organization = $request->attributes->get('organization');

        $department = $organization->departments()->findOrFail($departmentId);

        new DestroyDepartment(
            user: $request->user(),
            organization: $organization,
            department: $department,
        )->execute();

        return response()->noContent(204);
    }
}

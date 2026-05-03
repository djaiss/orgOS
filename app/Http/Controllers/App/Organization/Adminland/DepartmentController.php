<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Actions\CreateDepartment;
use App\Actions\DestroyDepartment;
use App\Actions\UpdateDepartment;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        $departments = $organization->departments()
            ->orderBy('name')
            ->get()
            ->map(fn($department) => (object) [
                'id' => $department->id,
                'name' => $department->name,
                'edit_link' => route('organization.adminland.department.edit', [
                    'slug' => $organization->slug,
                    'department' => $department->id,
                ]),
                'destroy_link' => route('organization.adminland.department.destroy', [
                    'slug' => $organization->slug,
                    'department' => $department->id,
                ]),
            ]);

        return view('app.organization.adminland.departments.index', [
            'departments' => $departments,
        ]);
    }

    public function create(): View
    {
        return view('app.organization.adminland.departments._create_department');
    }

    public function store(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        new CreateDepartment(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
        )->execute();

        return to_route('organization.adminland.department.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function edit(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        $id = $request->route()->parameter('department');
        try {
            $department = $organization->departments()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        return view('app.organization.adminland.departments._edit_department', [
            'department' => $department,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');

        $id = $request->route()->parameter('department');
        try {
            $department = $organization->departments()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        new UpdateDepartment(
            user: $request->user(),
            organization: $organization,
            department: $department,
            name: TextSanitizer::plainText($validated['name']),
        )->execute();

        return to_route('organization.adminland.department.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');

        $id = $request->route()->parameter('department');
        try {
            $department = $organization->departments()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        new DestroyDepartment(
            user: $request->user(),
            organization: $organization,
            department: $department,
        )->execute();

        return to_route('organization.adminland.department.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }
}

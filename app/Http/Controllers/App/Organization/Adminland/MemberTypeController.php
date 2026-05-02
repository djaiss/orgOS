<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Actions\CreateMemberType;
use App\Actions\DestroyMemberType;
use App\Actions\UpdateMemberType;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberTypeController extends Controller
{
    public function create(): View
    {
        return view('app.organization.adminland.members._create_member_type');
    }

    public function store(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        new CreateMemberType(
            user: $request->user(),
            organization: $organization,
            name: TextSanitizer::plainText($validated['name']),
        )->execute();

        return to_route('organization.adminland.member.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function edit(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        $id = $request->route()->parameter('memberType');
        try {
            $memberType = $organization->memberTypes()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        return view('app.organization.adminland.members._edit_member_type', [
            'memberType' => $memberType,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');

        $id = $request->route()->parameter('memberType');
        try {
            $memberType = $organization->memberTypes()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'position' => ['sometimes', 'integer', 'min:0'],
        ]);

        new UpdateMemberType(
            user: $request->user(),
            organization: $organization,
            memberType: $memberType,
            name: TextSanitizer::plainText($validated['name'] ?? $memberType->name),
            position: isset($validated['position']) ? (int) $validated['position'] : null,
        )->execute();

        return to_route('organization.adminland.member.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');

        $id = $request->route()->parameter('memberType');
        try {
            $memberType = $organization->memberTypes()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        new DestroyMemberType(
            user: $request->user(),
            organization: $organization,
            memberType: $memberType,
        )->execute();

        return to_route('organization.adminland.member.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }
}

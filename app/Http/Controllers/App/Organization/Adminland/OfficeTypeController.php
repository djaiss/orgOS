<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Actions\DestroyOfficeType;
use App\Actions\UpdateOfficeType;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeTypeController extends Controller
{
    public function edit(Request $request): View
    {
        $organization = $request->attributes->get('organization');
        $officeType = $request->attributes->get('officeType');

        if ($officeType->organization_id !== $organization->id) {
            abort(404);
        }

        return view('app.organization.adminland.offices._edit_office_type', [
            'officeType' => $officeType,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');
        $officeType = $request->attributes->get('officeType');

        if ($officeType->organization_id !== $organization->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        new UpdateOfficeType(
            user: $request->user(),
            organization: $organization,
            officeType: $officeType,
            name: TextSanitizer::plainText($validated['name']),
        )->execute();

        return redirect()->route('organization.adminland.office.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');
        $officeType = $request->attributes->get('officeType');

        if ($officeType->organization_id !== $organization->id) {
            abort(404);
        }

        new DestroyOfficeType(
            user: $request->user(),
            organization: $organization,
            officeType: $officeType,
        )->execute();

        return redirect()->route('organization.adminland.office.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }
}

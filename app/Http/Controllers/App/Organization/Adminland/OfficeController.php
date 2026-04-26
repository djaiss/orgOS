<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Actions\CreateOffice;
use App\Actions\DestroyOffice;
use App\Actions\UpdateOffice;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeController extends Controller
{
    public function index(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        [$offices, $officeTypes, $countries] = $this->viewData($organization);

        return view('app.organization.adminland.offices.index', [
            'offices' => $offices,
            'officeTypes' => $officeTypes,
            'countries' => $countries,
        ]);
    }

    public function create(Request $request): View
    {
        $organization = $request->attributes->get('organization');
        [, $officeTypes, $countries] = $this->viewData($organization);

        return view('app.organization.adminland.offices._create_office', [
            'officeTypes' => $officeTypes,
            'countries' => $countries,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');
        $validated = $this->validateRequest($request);

        new CreateOffice(
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

        return to_route('organization.adminland.office.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function edit(Request $request): View
    {
        $organization = $request->attributes->get('organization');
        $id = $request->route()->parameter('office');

        try {
            $office = $organization->offices()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        [, $officeTypes, $countries] = $this->viewData($organization);

        return view('app.organization.adminland.offices._edit_office', [
            'office' => $office,
            'officeTypes' => $officeTypes,
            'countries' => $countries,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');
        $id = $request->route()->parameter('office');

        try {
            $office = $organization->offices()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        $validated = $this->validateRequest($request);

        new UpdateOffice(
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

        return to_route('organization.adminland.office.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $organization = $request->attributes->get('organization');
        $id = $request->route()->parameter('office');

        try {
            $office = $organization->offices()->findOrFail($id);
        } catch (ModelNotFoundException) {
            abort(404);
        }

        new DestroyOffice(
            user: $request->user(),
            organization: $organization,
            office: $office,
        )->execute();

        return to_route('organization.adminland.office.index', $organization->slug)
            ->with('status', trans('Changes saved'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'office_type_id' => ['nullable', 'integer'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state_province' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country_id' => ['nullable', 'integer'],
            'timezone' => ['nullable', 'string', 'max:50'],
        ]);
    }

    /**
     * @return array{0: mixed, 1: mixed, 2: mixed}
     */
    private function viewData(Organization $organization): array
    {
        $officeTypes = $organization->officeTypes()
            ->orderBy('position')
            ->get()
            ->map(fn($officeType) => (object) [
                'id' => $officeType->id,
                'name' => $officeType->name,
                'edit_link' => route('organization.adminland.office_type.edit', [
                    'slug' => $organization->slug,
                    'officeType' => $officeType->id,
                ]),
                'destroy_link' => route('organization.adminland.office_type.destroy', [
                    'slug' => $organization->slug,
                    'officeType' => $officeType->id,
                ]),
            ]);

        $offices = $organization->offices()
            ->with('officeType', 'country')
            ->orderBy('name')
            ->get()
            ->map(fn($office) => (object) [
                'id' => $office->id,
                'name' => $office->name,
                'office_type' => $office->officeType?->name,
                'address' => (string) $office->address,
                'edit_link' => route('organization.adminland.office.edit', [
                    'slug' => $organization->slug,
                    'office' => $office->id,
                ]),
                'destroy_link' => route('organization.adminland.office.destroy', [
                    'slug' => $organization->slug,
                    'office' => $office->id,
                ]),
            ]);

        $countries = Country::query()
            ->orderBy('name')
            ->get()
            ->map(fn($country) => (object) [
                'id' => $country->id,
                'name' => $country->name,
            ]);

        return [$offices, $officeTypes, $countries];
    }
}

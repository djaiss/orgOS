<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Organization;

use App\Actions\CreateOrganization;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(Request $request): View
    {
        $organizations = $request
            ->user()
            ->organizations()
            ->get()
            ->map(fn (Organization $organization) => (object) [
                'name' => $organization->name,
                'link' => route('organization.show', $organization->slug),
                'avatar' => $organization->getAvatar(),
            ]);

        return view('app.organization.index', [
            'organizations' => $organizations,
        ]);
    }

    public function create(): View
    {
        return view('app.organization.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_]+$/',
            ],
        ]);

        $organization = new CreateOrganization(
            user: $request->user(),
            name: TextSanitizer::plainText($validated['organization_name']),
        )->execute();

        return redirect()
            ->route('organization.show', $organization->slug)
            ->with('status', __('Organization created successfully'));
    }

    public function show(Request $request): View
    {
        return view('app.organization.show', [
            'organization' => $request->attributes->get('organization'),
        ]);
    }
}

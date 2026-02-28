<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization;

use App\Actions\JoinOrganization;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JoinOrganizationController extends Controller
{
    public function create(): View
    {
        return view('app.organization.join.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invitation_code' => [
                'required',
                'string',
                'max:64',
            ],
        ]);

        $organization = new JoinOrganization(
            user: $request->user(),
            invitationCode: TextSanitizer::plainText($validated['invitation_code']),
        )->execute();

        return to_route('organization.show', $organization->slug)
            ->with('status', __('Welcome '));
    }
}

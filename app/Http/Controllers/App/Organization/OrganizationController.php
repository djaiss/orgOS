<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
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
                'link' => route('organizations.show', $organization->slug),
                'avatar' => $organization->getAvatar(),
            ]);

        return view('app.organization.index', [
            'organizations' => $organizations,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        $members = $organization->members()
            ->with('user')
            ->get()
            ->map(fn ($member) => (object) [
                'name' => $member->user?->getFullName(),
                'email' => $member->user?->email,
                'joined_at' => $member->joined_at,
            ]);

        return view('app.organization.adminland.members.index', [
            'organization' => $organization,
            'members' => $members,
        ]);
    }
}

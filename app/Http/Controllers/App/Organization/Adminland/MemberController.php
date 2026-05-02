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
            ->orderByDesc('joined_at')
            ->get()
            ->map(fn($member) => (object) [
                'name' => $member->user?->getFullName(),
                'email' => $member->user?->email,
                'joined_at' => $member->joined_at,
                'permission' => $member->permission,
            ]);

        $memberTypes = $organization->memberTypes()
            ->orderBy('position')
            ->get()
            ->map(fn($memberType) => (object) [
                'id' => $memberType->id,
                'name' => $memberType->name,
                'position' => $memberType->position,
                'update_link' => route('organization.adminland.member_type.update', [
                    'slug' => $organization->slug,
                    'memberType' => $memberType->id,
                ]),
                'edit_link' => route('organization.adminland.member_type.edit', [
                    'slug' => $organization->slug,
                    'memberType' => $memberType->id,
                ]),
                'destroy_link' => route('organization.adminland.member_type.destroy', [
                    'slug' => $organization->slug,
                    'memberType' => $memberType->id,
                ]),
            ]);

        return view('app.organization.adminland.members.index', [
            'members' => $members,
            'memberTypes' => $memberTypes,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InviteMemberController extends Controller
{
    public function index(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        return view('app.organization.adminland.members.invite.index', [
            'organization' => $organization,
        ]);
    }
}

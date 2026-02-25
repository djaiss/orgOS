<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Actions\CreateOrganization;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminlandController extends Controller
{
    public function index(Request $request): View
    {
        return view('app.organization.adminland.index', [
            'organization' => $request->attributes->get('organization'),
        ]);
    }
}

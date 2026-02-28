<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Http\Controllers\Controller;
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

<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeController extends Controller
{
    public function index(Request $request): View
    {
        $organization = $request->attributes->get('organization');

        $officeTypes = $organization->officeTypes()
            ->orderBy('position')
            ->get()
            ->map(fn($office) => (object) [
                'name' => $office->name,
            ]);

        $offices = $organization->offices()
            ->with('officeType', 'country')
            ->orderBy('name')
            ->get()
            ->map(fn($office) => (object) [
                'name' => $office->name,
                'office_type' => $office->officeType?->name,
                'country' => $office->country?->name,
            ]);

        return view('app.organization.adminland.offices.index', [
            'offices' => $offices,
            'officeTypes' => $officeTypes,
        ]);
    }
}

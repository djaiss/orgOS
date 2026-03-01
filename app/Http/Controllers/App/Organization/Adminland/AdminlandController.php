<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization\Adminland;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminlandController extends Controller
{
    public function index(): View
    {
        return view('app.organization.adminland.index');
    }
}

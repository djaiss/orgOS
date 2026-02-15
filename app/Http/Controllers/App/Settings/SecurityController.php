<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityController extends Controller
{
    public function index(Request $request): View
    {
        return view('app.settings.security.index', [
            'has2fa' => $request->user()->two_factor_confirmed_at !== null,
        ]);
    }
}

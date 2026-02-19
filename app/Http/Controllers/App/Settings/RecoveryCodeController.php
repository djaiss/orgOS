<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecoveryCodeController extends Controller
{
    public function show(Request $request): View
    {
        $recoveryCodes = $request->user()->two_factor_recovery_codes ?? [];

        return view('app.settings.security._recovery-codes', [
            'recoveryCodes' => collect($recoveryCodes),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Settings;

use App\Actions\ToggleAutoDeleteAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AutoDeleteAccountController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'auto_delete_account' => ['required', 'in:yes,no'],
        ]);

        new ToggleAutoDeleteAccount(
            user: $request->user(),
            autoDeleteAccount: $request->input('auto_delete_account') === 'yes',
        )->execute();

        return to_route('settings.security.index')
            ->with('status', trans('Changes saved'));
    }
}

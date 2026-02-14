<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Settings;

use App\Actions\UpdateUserPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'max:255'],
            'new_password' => [
                'required',
                'string',
                'max:255',
                'confirmed',
                Password::min(8)->uncompromised(),
            ],
        ]);

        new UpdateUserPassword(
            user: $request->user(),
            currentPassword: $validated['current_password'],
            newPassword: $validated['new_password'],
        )->execute();

        return to_route('settings.security.index')
            ->with('status', __('Changes saved'));
    }
}

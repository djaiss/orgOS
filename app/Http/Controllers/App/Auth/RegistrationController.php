<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Auth;

use App\Actions\CreateAccount;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        $quotes = config('quotes');
        $randomQuote = $quotes[array_rand($quotes)];

        return view('app.auth.register', [
            'quote' => $randomQuote,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                'disposable_email',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
                'confirmed',
                Password::min(8)->uncompromised(),
            ],
        ]);

        $user = new CreateAccount(
            email: mb_strtolower((string) $validated['email']),
            password: $validated['password'],
            firstName: TextSanitizer::plainText($validated['first_name']),
            lastName: TextSanitizer::plainText($validated['last_name']),
        )->execute();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('organization.index', absolute: false));
    }
}
